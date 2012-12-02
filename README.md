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
    
Usage
-----

Add this to your controller:

    public $components = array('Saml.Saml');
    
You can now call the following to require a user to be logged in to view the current page:

    $this->Saml->requireAuth();
    
Troubleshooting
---------------

Check the following logs for possible information:

* CakePHP error log (default is `app/tmp/logs/error.log`)
* Apache error log
* PHP error log (if different from the Apache error log)
* simpleSAMLphp log