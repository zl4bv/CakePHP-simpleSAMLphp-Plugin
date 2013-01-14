<?php 

App::uses('Component', 'Controller');

/**
 * Allows the Saml plugin to integrate with the CakePHP Authentication library.
 * @author Ben Vidulich
 * @link http://book.cakephp.org/2.0/en/core-libraries/components/authentication.html
 *
*/
class SamlAuthenticate extends Component {
	public $components = array('Saml.Saml');

	/**
	 * Authenticate a user based on the request information.
	 *
	 * @param CakeRequest $request Request to get authentication information from.
	 * @param CakeResponse $response A response object that can have headers added.
	 * @return mixed Either false on failure, or an array of user data on success.
	*/
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		return $this->Saml->getAttributes();
	}

	/**
	 * Get user information.  
	 *
	 * @return mixed Either false or an array of user information
	 */
	public function getUser() {
		return $this->Saml->getAttributes();
	}
	
	/**
	 * Logs the user out.
	 */
	public function logout() {
	}
}

?>