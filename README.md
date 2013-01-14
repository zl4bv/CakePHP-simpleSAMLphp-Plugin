CakePHP-simpleSAMLphp-Plugin
==================

Adds support for SAML authentication to CakePHP 2.x.

Requirements
------------

* Latest version of simpleSAMLphp (>= 1.9.0)
* Latest version of CakePHP (>= 2.2.3)

Installation
------------

Simply drop the plugin folder in the `app/Plugin` folder.

The plugin needs to be loaded manually in `app/Config/bootstrap.php`.

You can either load the plugin in by name, or load all plugins with a single call:

    CakePlugin::loadAll(); // Loads all plugins at once
    CakePlugin::load('Saml'); //Loads a single plugin

Configuration
-------------

Configuration is done in `app/Config/core.php`.

Path to SimpleSAMLphp installation.

    Configure::write('Saml.SimpleSamlPath', '/path/to/simpleSAMLphp');

Authentication source to use. Defaults to 'default-sp'.

    Configure::write('Saml.AuthSource', 'default-sp');

Usage (CakePHP Custom Authentication Object)
------------------------------
This methods allows you to use simpleSAMLphp as a custom authentication object in simpleSAMLphp.

In your `AppController.php` or other controller, add the following to start authenticating with this plugin:

	public $components = array(
			'Auth' => array(
					'authenticate' => array('Saml.Saml')
			));
			
To access the plugin in your controller:

	public $components = array('Saml.Saml');
			
You will then need to create a login action to log users in:

	public function login() {
		if ($this->Saml->isAuthenticated()) {
			return $this->redirect($this->Auth->redirect());
		} else {
			$this->Saml->login();
		}
	}
	
Note: You can optionally supply an array of parameters to `$this->Saml->login()` and these will be passed to simpleSAMLphp. See [here](http://simplesamlphp.org/docs/stable/saml:sp) for a full list of parameters.

Now create an action to log users out:

	public function logout() {
		if ($this->Saml->isAuthenticated()) {
			$this->Saml->logout();
		} else {
			$this->redirect($this->Auth->logout());
		}
	}
	
Note: Again, you can supply an array of parameters to `$this->Saml->logout()` and these will be passed to simpleSAMLphp.

More information about authentication in CakePHP can be found [here](http://book.cakephp.org/2.0/en/core-libraries/components/authentication.html).

Usage (Manual usage)
--------------------
This allows you to fully control the authentication process. It allows you to call methods that are directly mapped to the SimpleSAML_Auth_Simple class.

Add this to your controller:

    public $components = array('Saml.Saml');
    
You can then call the following to require a user to be logged in to view the current page:

    $this->Saml->requireAuth();
    
Refer to `Controller/Component/SamlComponent.php` inside the plugin or the [simpleSAMLphp SP API reference](http://simplesamlphp.org/docs/stable/simplesamlphp-sp-api) for more information.
    
Troubleshooting
---------------

Check the following logs for possible information:

* CakePHP error log (default is `app/tmp/logs/error.log`)
* Apache error log
* PHP error log (if different from the Apache error log)
* simpleSAMLphp log