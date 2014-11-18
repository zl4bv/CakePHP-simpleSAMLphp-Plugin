# Example: simple-sp

Example SAML service provider application to demonstrate the CakePHP-simpleSAMLphp plugin.

## Requirements

* CakePHP (tested with version 2.5.2)
* SimpleSAMLphp (tested with version 1.13.2)

## Installation

1. Download and install fresh copies of CakePHP and SimpleSAMLphp
2. Configure your web server for CakePHP and set up a basic auth source in SimpleSAMLphp
3. Configure SimpleSAMLphp to use a different [store type](https://github.com/simplesamlphp/simplesamlphp/blob/master/config-templates/config.php#L608) e.g. 'sql'
4. Download this example directory and place over the top of the CakePHP APP directory
5. Browse your newly created CakePHP website and attempt to visit the home page
6. You should be redirected to your SAML authentication page and then back to the standard CakePHP home page
