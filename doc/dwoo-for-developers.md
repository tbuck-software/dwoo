# Dwoo for developers

This chapter describes the API to Dwoo and not the template language. It will be most useful as reference to those implementing the template interface to the application and not those who are creating Dwoo templates.

## Basics
```php
//index.php
<?php
// Include the main class and register autoloader class (it should handle the rest on its own)
require 'vendor/autoload.php';

// Create a Dwoo core object
$core = new Dwoo\Core();

// Create some data
$data = array('a'=>5, 'b'=>6);

// Output the result
echo $core->get('path/to/index.tpl', $data);
```
## File & Data objects
```php
// index.php
<?php
require 'vendor/autoload.php';

$core = new Dwoo\Core();

// Load a template file, this is reusable if you want to render multiple times the same template with different data
$tpl = new Dwoo\Template\File('path/to/index.tpl');

// Create a data set, this data set can be reused to render multiple templates if it contains enough data to fill them all
$data = new Dwoo\Data();

// Fill it with some data 
$data->assign('foo', 'BAR');
$data->assign('bar', 'BAZ');

// Output the result
echo $core->get($tpl, $data);
```
## Compiler object
```php
// index.php
<?php
require 'vendor/autoload.php';

$core = new Dwoo\Core();
$tpl = new Dwoo\Template\File('path/to/index.tpl');
$data = array('a' => 5, 'b' => 6);
 
// Create the compiler instance
$compiler = new \Dwoo\Compiler();
// Add a pre-processor that is in one of the plugin directories
$compiler->addPreProcessor('Processor_Name', true);
// .. Or a custom filter you made
$compiler->addPreProcessor('Processor_Function_Name');
 
// Output the result and provide the compiler to use
echo $core->get($tpl, $data, $compiler);
```
## Set directories
In this example, we wild see how to use `setCompileDir` and `setTemplateDir` methods.
```php
// index.php
<?php
// Include the main class and register autoloader class (it should handle the rest on its own)
require 'vendor/autoload.php';

// Create a Dwoo core object
$core = new Dwoo\Core();

// Configure directories
$core->setCompileDir('path/to/compiled/dir/'); // Folder to store compiled templates
$core->setTemplateDir('path/to/your/template/dir/'); // Folder containing .tpl files

// Create some data
$data = array('a'=>5, 'b'=>6);

// Output the result
echo $core->get('index.tpl', $data);
```