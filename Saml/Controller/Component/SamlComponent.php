<?php
/**
 * Simple component to bind to the SimpleSAML_Auth_Simple class.
 * @author Ben Vidulich
 * @link http://simplesamlphp.org/docs/stable/simplesamlphp-sp-api
 *
 */
class SamlComponent extends Component {
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
	 * Retrieve the attributes of the current user. If the user isn't authenticated, an empty array will be returned.
	 */
	public function getAttributes() {
		return $this->as->getAttributes();
	}
	
	/**
	 * Retrieve the specified authentication data for the current session. NULL is returned if the user isn't authenticated.
	 * 
	 * @param string $name
	 */
	public function getAuthData(string $name) {
		return $this->as->getAuthData($name);
	}
	
	/**
	 * Retrieves the URL that can start the authentication process.
	 * 
	 * @param string $returnTo The URL the user will be returned to. By default it is the current page.
	 */
	public function getLoginURL(string $returnTo = NULL) {
		return $this->as->getLoginURL($returnTo);	
	}
	
	/**
	 * Retrieves the URL that can trigger a logout.
	 *
	 * @param string $returnTo The URL the user will be returned to. By default it is the current page.
	 */
	public function getLogoutURL(string $returnTo = NULL) {
		return $this->as->getLogoutURL($returnTo);
	}

	/**
	 * Returns TRUE if the user is authenticated.
	 */
	public function isAuthenticated() {
		return $this->as->isAuthenticated();
	}

	/**
	 * Starts the authentication process.
	 *
	 * @param mixed $url The URL to redirect to after logging in, or an associative array of parameters.
	 */
	public function login(mixed $url) {
		$this->as->login($url);
	}

	/**
	 * Logs the user out.
	 *
	 * @param mixed $url The URL to redirect to after logging out, or an associative array of parameters.
	 */
	public function logout(mixed $url) {
		$this->as->logout($url);
	}

	/**
	 * Ensures the user is authenticated. Starts the authentication process if the user isn't authenticated.
	 *
	 * @param array $params Associative array with named parameters. See the simplePHPphp documentation for a description of the paramters.
	 */
	public function requireAuth(array $params = array()) {
		$this->as->requireAuth($params);
	}
}
?>