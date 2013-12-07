<?php
/** This Authenticate component allows a double authentication (SAML and FormAuthenticate)
  * It uses code from Ben Vidulich to integrate SimpleSamlPHP with CakePHP https://github.com/bvidulich/CakePHP-simpleSAMLphp-Plugin
  * Customized by Jeff Stich (scith)
 */

App::uses('FormAuthenticate', 'Controller/Component/Auth');

class SamlFormAuthenticate extends FormAuthenticate {

	private $path = NULL;
	private $authSource = 'default-sp';
	private $as;

	/**
	 * Initializes a SimpleSAML_Auth_Simple object.
	 *
	 * @param string $authSource The ID of the authentication source to use. This will
	 * override the authentication source set in `app/Config/bootstrap.php`.
	 */
	public function __construct(ComponentCollection $collection, array $settings, string $authSource = NULL) {
		// Check the config
		if (Configure::read('Saml.SimpleSamlPath') != NULL) {
			$this->path = Configure::read('Saml.SimpleSamlPath');
		} else {
			throw new Exception('Parameter Saml.SimpleSamlPath is missing from the configuration file.');
		}
		
		if ($authSource != NULL) {
			$this->authSource = $authSource;
		} elseif (Configure::read('Saml.AuthSource') != NULL) {
			$this->authSource = Configure::read('Saml.AuthSource');
		}
		
		// Initialize simpleSAMLphp
		require_once($this->path.'/lib/_autoload.php');
		$this->as = new SimpleSAML_Auth_Simple($this->authSource);
	}


/**
 * Authenticates the identity contained in a request. Will use the `settings.userModel`, and `settings.fields`
 * to find POST data that is used to find a matching record in the `settings.userModel`. Will return false if
 * there is no post data, either username or password is missing, or if the scope conditions have not been met.
 *
 * @param CakeRequest $request The request that contains login information.
 * @param CakeResponse $response Unused response object.
 * @return mixed False on login failure. An array of User data on success.
 */

 	public function authenticate(CakeRequest $request, CakeResponse $response) {
		$userModel = $this->settings['userModel'];
		list(, $model) = pluginSplit($userModel);

		// If the user is already authenticated in SimpleSamlPHP
		if ($this->as->isAuthenticated())
		{
			$user = $this->as->getAttributes();
			// The SAML attributes are sent to the session
			/** This is where you should add code if you want to unify SAML attributes with users in database.
			  * Check if it exists in DB with _findUser($username) (using 'uid' as 'username' for example)
				*	If Yes (_findUser returns true) -> send it to $user
				*	If No (_findUser returns false) -> add it in the DB (or print "have to create an account first", or anything you want)
			 */
		}

		// Else if the user logins via a Form, attributes are retrieved in the database (standard AuthForm) and sent to the session
		else {
			$fields = $this->settings['fields'];
			if (!$this->_checkFields($request, $model, $fields)) {
				return false;
			}

			// Try to find the user in the database with the information provided
			$user = $this->_findUser(
				$request->data[$model][$fields['username']],
				$request->data[$model][$fields['password']]
			);
		}

		// If wrong information, return false
		if (!$user) {
			return false;
		}

		// Else return the user token
		return $user;
	}

	// This function hooks into AuthComponent::logout and extends it to add a SAML logout in addition to the session logout
	public function logout($user) {
	    if ($this->as->isAuthenticated()) {
	        $this->as->logout();
	    }
	}

}