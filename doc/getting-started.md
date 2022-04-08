# Getting started
Dwoo relies on plugins to provide useful functionality, examples may include: if, else blocks, include functionality, string manipulation and loops. For a full list see the [plugins] page.

## Prerequisites
Dwoo `1.3` requires at least PHP 5.3 to run.

> Since version `1.3.1` Dwoo is now compatible with **PHP7**.

## Installation
### Installing via Composer (recommended)
Dwoo is available on packagist.org to do so, install Composer and run the following command to get the latest version:
```bash
composer require dwoo/dwoo 1.3.*
```
### Installing from the tarball release
1. Download the most recent tarball from the releases page,
2. Unpack the tarball,
3. Move the files somewhere in your project,
4. Go to the next section to know how to use classes.

## Running Dwoo
### Dwoo at its simplest
```php
// index.php
<?php 

// Include the main class, the rest will be automatically loaded
require 'vendor/autoload.php';

// Create the controller, it is reusable and can render multiple templates
$core = new Dwoo\Core();

// Create some data
$data = array('a'=>5, 'b'=>6);

// Output the result
echo $core->get('path/to/index.tpl', $data);
```

```html
<!-- index.html / index.tpl -->
<html>
	<body>
		<h1>{$a}</h1>
		<p>{$b}</p>
	</body>
</html>
```

## Using Template and Data objects
In this example we add the `Dwoo\Data` class that serves as a data container, and the `Dwoo\Template\File` class that represents a template file in your application.

```php
// index.php
<?php

// Include the main class, the rest will be automatically loaded
require 'vendor/autoload.php';

// Create the controller, it is reusable and can render multiple templates
$core = new Dwoo\Core();

// Load a template file, this is reusable if you want to render multiple times the same template with different data
$tpl = new Dwoo\Template\File('path/to/index.tpl');

// Create a data set, this data set can be reused to render multiple templates if it contains enough data to fill them all
$data = new Dwoo\Data();
// Fill it with some data
$data->assign('foo', 'BAR');
$data->assign('bar', 'BAZ');

// Output the result
echo $core->output($tpl, $data);
```

## Using a Compiler object
If you want to use custom [pre-processors or post-processors], you need to instantiate a `Dwoo\Compiler` and add the processors to it.
```php
// index.php
<?php 
require 'vendor/autoload.php';

$core = new Dwoo\Core();
$tpl = new Dwoo\Template\File('path/to/index.tpl');
$data = array('a'=>5, 'b'=>6);

// Create the compiler instance
$compiler = new Dwoo\Compiler();
// Add a pre-processor that is in one of the plugin directories
$compiler->addPreProcessor('Processor_Name', true);
// .. Or a custom filter you made
$compiler->addPreProcessor('Processor_Function_Name');

// Output the result and provide the compiler to use
echo $core->get($tpl, $data, $compiler);
```
## Using Dwoo with loops - Blog example
Let's assume you are looping over multiple articles of a blog that you want to display, here is what you can do to do it as lightly as possible:

You first have to create an `article.tpl` template file, the name doesn't matter really it's up to you, here is what goes in:
```html
<div class="article">
	<h1>{$title}</h1>
	{$content}
	<p class="footer">posted by {$author} on {date_format $date "%d/%m/%Y"}</p>
</div>
```
You will then use this template to render all the articles.
```php
// index.php
<?php
require 'vendor/autoload.php';

$core = new Dwoo\Core();
// Load the "article" template
$tpl = new Dwoo\Template\File('path/to/article.tpl');

// Retrieve your data using whatever means you use
$articles = getMyArticles();

// Loop over them
foreach($articles as $article) {
    // Output each article using their data (assuming it is an
    // associative array containing "title", "content", "author"
    // and "date" keys)
    echo $core->get($tpl, $article);
}
```

## Basic Templating
As already mentioned, the Dwoo runtime parses the template files to generate HTML output. In this section we introduce some common constructs to quickly get you up to speed.

### Variables
#### Simple variables
As above in Dwoo simple variables are passed and rendered to HTML using an associated array, as the following example illustrates:

```html
<!-- index.html / index.tpl -->
<h1>{$page_title}</h1>
<div id="content">
   {$page_content}
</div>
```
The variables page_title and page_content are passed to the template rendering API like such:

```php
// index.php
<?php
$core = new Dwoo\Core();
 
$params = array();
$params['page_title']   = 'The next social networking website';
$params['page_content'] = 'Make friends online? Y/N';
 
echo $core->get("code_snippet.tpl", $params);
```

In the above example the $params array is loaded with parameters that are substituted in the Dwoo code snippet.

In Dwoo it is possible to "bundle" your variables up into arrays on the PHP side and address them separately in a Dwoo template using the "dot" operator. This helps reduce the possibility of confusion with one variable with two different purposes being used in different places in your Dwoo template. An example will be used to illustrate this:

```html
<!-- index.html / index.tpl -->
<div id="action-bar">welcome {$auth.username} | <a href="logout.php">logout...</a></div>
 
<h1>{$page.title}</h1>
<div id="content">
   {$page.content}
</div>
```
In the above code snippet we have two bundles, the first is the auth array that includes the userâs username. The second is the array that contains the page content. This snippet is setup with the following PHP code:

```php
// index.php
<?php
$core = new Dwoo\Core();
 
/* We hard code the parameters in here but in a real world app this would come from 
 * an authenticating module using a DB or maybe from an LDAP server. 
*/
$auth = array();
$auth['username'] = 'corey';
$auth['ok'] = true;
$auth['is_admin'] = false;
 
/* Load the page content. */
$page = array();
$page['title']   = 'The next social networking website';
$page['content'] = 'Make friends online? Y/N';
 
$params = array();
$params['auth']    = $auth;
$params['page'] = $page;
 
echo $core->get("code_snippet.tpl", $params);
```
The above two snippets generate roughly the same HTML as the first example using simple variables. You may wonder why using arrays to bundle your parameters has any benefit:

1. It prevents naming collisions.
2. Allows you to group or encapsulate related blocks of variables.
3. You can have different PHP modules generating their own output arrays making your siteâs design more modularized.

## Conditionals
Dwoo has if/else constructs to allow the coder to add conditional sections to web pages. This can be used for things such as restricting content to authorized users or displaying form components only if certain conditions are met. Dwooâs conditional statements are quite flexible, however we only cover the basics here.

## Loops
Sometimes you need to repeat parts of a web page in a systematic manor. In Dwoo this is done using loops. This is best illustrated by an example:

```html
<!-- index.html / index.tpl -->
<select name="type_id" value="{$type_id}">
 {loop $licensee_type_list}
   <option value="{$id}">{$name}</option>
 {/loop}
</select>
```
Above is a code snippet from an HTML form. The type_id is a simple variable (see above). The licensee_type_list variable is an array containing associated arrays. What occurs is that in every iteration of the loop the loop plugin extracts the values from an array entry with its name and id as keys and substitutes them into the template. The above snippet (if in a file called code_snippet.tpl) would be initialized and setup with the following PHP:
```php
// index.php
<?php
$core = new Dwoo\Core();
 
/* Although we are populating this by hand it will usually come from a DB in practice.  */
 
$type_list = array();
$type_list[] = array('id' => 1, 'name' => 'Machine');
$type_list[] = array('id' => 2, 'name' => 'Individual');
$type_list[] = array('id' => 3, 'name' => 'Group');
$type_list[] = array('id' => 4, 'name' => 'Site');
 
/* Load the params array and render the output. */
 
$params = array();
$params['type_id']            = 1;           // from a DB...
$params['licensee_type_list'] = $type_list
 
echo $core->get("code_snippet.tpl", $params);
```
The above Dwoo snippet and PHP code extract will give the following HTML output:

```html
<!--select.html / select.tpl-->
<select name="type_id" value="1">
   <option value="1">Machine</option>
   <option value="2">Individual</option>
   <option value="3">Group</option>
   <option value="4">Site</option>
</select>
```
This method can also be used to generate tables of data (by repeating the tr tag). It is up to you.

## Breaking your webpage templates into sections
Webpages are usually broken up into sections for easy maintenance and design. An example could be a webpage which has a header, body content, and footer sections. In this case instead of everything being in one file, the page is broken up into components which can be reused for other page templates.

In PHP you can do this by writing functions, that when called output a section of the page. In Dwoo this is accomplished by using the include plugin. An example would be:
```html
<!-- sect_header_stuff.html / sect_header_stuff.tpl -->
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>{$title} - Awesome Inc.</title>
 
    <script src="script.js" type="text/javascript" language="javascript"></script>
    <link href="site.css" type="text/css" rel="stylesheet"/>
  </head>
  <body> <!-- Page content START. -->
```
```html
<!-- sect_footer_stuff.html / sect_footer_stuff.tpl -->
  </body> <!-- Page content END. -->
</html>
```
```html
<!-- webpage_generic.html / webpage_generic.tpl -->
{include(file='sect_header_stuff.tpl')}
 
<div id="content">
  {$content}
</div>
 
{include(file='sect_footer_stuff.tpl')}
```
Above is a generic page whose title and main body content can be extracted from a database. You may choose to do it this way or to âhard codeâ your content directly into a function specific template.