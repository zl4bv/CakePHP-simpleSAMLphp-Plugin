# CakePHP-simpleSAMLphp-Plugin

Adds support for SAML authentication to CakePHP 2.x.

## Requirements

* Latest version of simpleSAMLphp (>= 1.9.0)
* Latest version of CakePHP (>= 2.2.3)

## Installation

Simply drop the *Saml* folder in the `app/Plugin` folder.

The plugin needs to be loaded manually in `app/Config/bootstrap.php`.

You can either load the plugin in by name, or load all plugins with a single call:

```php
    CakePlugin::loadAll(); // Loads all plugins at once
    CakePlugin::load('Saml'); //Loads a single plugin
```

## Configuration

Configuration is done in `app/Config/core.php`.

Path to SimpleSAMLphp installation.

```php
    Configure::write('Saml.SimpleSamlPath', '/path/to/simpleSAMLphp');
```

Authentication source to use. Defaults to 'default-sp'.

```php
    Configure::write('Saml.AuthSource', 'default-sp');
```

## Usage (CakePHP Custom Authentication Object)

This methods allows you to use simpleSAMLphp as a custom authentication object in simpleSAMLphp.

In your `AppController.php` or other controller, add the following to start authenticating with this plugin:

```php
	public $components = array(
			'Auth' => array(
					'authenticate' => array('Saml.Saml')
			));
```
			
To access the plugin in your controller:

```php
	public $components = array('Saml.Saml');
```
			
You will then need to create a login action to log users in:

```php
	public function login() {
		if ($this->Saml->isAuthenticated()) {
			return $this->redirect($this->Auth->redirect());
		} else {
			$this->Saml->login();
		}
	}
```

Note: You can optionally supply an array of parameters to `$this->Saml->login()` and these will be passed to simpleSAMLphp. See [here](http://simplesamlphp.org/docs/stable/saml:sp) for a full list of parameters.

Now create an action to log users out:

```php
	public function logout() {
		if ($this->Saml->isAuthenticated()) {
			$this->Saml->logout();
		} else {
			$this->redirect($this->Auth->logout());
		}
	}
```

Note: Again, you can supply an array of parameters to `$this->Saml->logout()` and these will be passed to simpleSAMLphp.

More information about authentication in CakePHP can be found [here](http://book.cakephp.org/2.0/en/core-libraries/components/authentication.html).

## Usage (Manual usage)

This allows you to fully control the authentication process. It allows you to call methods that are directly mapped to the SimpleSAML_Auth_Simple class.

Add this to your controller:

```php
    public $components = array('Saml.Saml');
```
    
You can then call the following to require a user to be logged in to view the current page:

```php
    $this->Saml->requireAuth();
```
    
Refer to `Controller/Component/SamlComponent.php` inside the plugin or the [simpleSAMLphp SP API reference](http://simplesamlphp.org/docs/stable/simplesamlphp-sp-api) for more information.
    
## Troubleshooting

Check the following logs for possible information:

* CakePHP error log (default is `app/tmp/logs/error.log`)
* Apache error log
* PHP error log (if different from the Apache error log)
* simpleSAMLphp log

## Use SamlForm to authenticate using both SimpleSamlPHP and Auth->FormAuthenticate

First, load the custom authenticate method:

```php
	public $components = array(
		'Saml.Saml',
		'Auth' => array(
	               'authenticate' => array('Saml.SamlForm'))
	);
```
	
Then use it as you would normally use any FormAuthenticate. For example:
A login page could have both a form to login manually and a link to login via SimpleSamlPHP. The controller would then have something like this:

```php
	if($this->Auth->login())
	{
		$this->redirect($this->Auth->redirectUrl());
	}
	// Generate the samlLoginLink and after authenticate redirect to the same page
		$samlLoginLink = $this->Saml->getLoginURL(Router::url($this->here, true));
		$this->set(compact('samlLoginLink'));
```

To logout of BOTH the session and SimpleSamlPHP, it is as simple as this:

```php
	$this->Auth->logout();
```
	
Note: in SamlFormAuthenticate.php you can add custom operations in authenticate().
For example, you could unify SAML attributes with users in the database. If the SAML user does not exist -> create it the database using 'uid' as 'username'. If the SAML user already exists -> fetch the info in the database.
This way, you could have a unified database using both your own users and SAML users.
