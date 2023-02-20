# Dwoo for designers
This document describes the syntax and semantics of the template engine and will be most useful as reference to those creating Dwoo templates.


## Synopsis
A template is simply a text file. It can generate any text-based format (HTML, XML, TPL, etc.). It doesnât have a specific extension, .html or .tpl are just fine.

A template contains `variables` or `expressions`, which get replaced with values when the template is evaluated, and `tags`, which control the logic of the template.

Below is a minimal template that illustrates a few basics. We will cover the details later on:
```php
<!DOCTYPE html>
<html>
    <head>
        <title>My Webpage</title>
    </head>
    <body>
        <ul id="navigation">
        {foreach $navigation item}
            <li><a href="{$item.href}">{$item.caption}</a></li>
        {/foreach}
        </ul>

        <h1>My Webpage</h1>
        {$a_variable}
    </body>
</html>
```

## Delimiters
All Dwoo template tags are enclosed within delimiters. By default these are `{` and `}`, but they can be changed.

For the examples in this article, we will assume that you are using the default delimiters.
## Comments
To comment-out part of a line in a template, use the comment syntax `{* ... *}`.  
These comments are handled by Dwoo, and are not sent as output to the browser, unlike HTML comments.  
This is useful for debugging or to add information for other template designers or yourself:
```html
{* This is a Dwoo comment *}
 
{*
 * This is a multi line
 * Dwoo comment!
 *}
 
{*
  This is also a comment
*}
```
## Variables
The application passes variables to the templates you can mess around in the template. Variables may have attributes or elements on them you can access too. How a variable looks like heavily depends on the application providing those.

You can use a dot `.` to access attributes of a variable (methods or properties of a PHP object, or items of a PHP array), or the so-called **subscript** syntax `[]`:
```php
{$foo}
{$foo.bar}
{$foor['bar']}
```
### Global variables
The following variables are always available in templates:

- `{$.version}` : Version number of Dwoo
- `{$.ad}` : Link to dwoo website
- `{$.now}` : Request time to execute the page
- `{$.charset}` : Return the used charset (default: UTF-8)

## Functions
Functions can be called to generate content. Functions are called by their name followed by parentheses and may have arguments.
For example with the function `date_format`:
```php
{date_format "now" "Y-m-j"}
```
### PHP native functions
Dwoo allow users to use PHP native functions within the template.
Default allowed PHP functions are:

- str_repeat
- number_format
- htmlentities
- htmlspecialchars
- long2ip
- strlen
- list
- empty
- count
- sizeof
- in_array
- is_array

To use a PHP function within your template, you can use them like the next examples:
```php
{number_format($foo 2 "," " ")}
{str_repeat("-=" 10)}
```
## Named Arguments
Using named arguments makes your templates more explicit about the meaning of the values you pass as arguments:
```php
{date_format value="now" format"Y-m-j"}
```
Named arguments also allow you to skip some arguments for which you donât want to change the default value:
```php
{date_format format="m/d/y" timestamp=$.now modify="+150 day"}
```
## HTML escaping
this code
```php
{"some <strong>html</strong> tags"|escape}
```
will output
```php
some &lt;strong&gt;html&lt;/strong&gt; tags
```
By default, the escape filter uses the html strategy but depending on the escaping context, you might want to explicitly use any other available strategies:
```php
{"some <strong>html</strong> tags"|escape('htmlall')}
{"some <strong>html</strong> tags"|escape('url')}
{"some <strong>html</strong> tags"|escape('urlpathinfo')}
{"some <strong>html</strong> tags"|escape('quotes')}
{"some <strong>html</strong> tags"|escape('hex')}
{"some <strong>html</strong> tags"|escape('hexentity')}
{"some <strong>html</strong> tags"|escape('javascript')}
{"some <strong>html</strong> tags"|escape('mail')}
```
## Including other Templates
The include function is useful to include a template and return the rendered content of that template into the current one:
```php
{include('header.html')}
```
Sometimes you may need to pass variables to another template without wanting to include them in the data parameter of the get or output methods of the Dwoo class. This can be done by adding them in as parameters as the following example illustrates:
```php
{include(file='site_header.tpl' title='About Us')}
```


## Template Inheritance
The most powerful part of Dwoo is template inheritance. Template inheritance allows you to build a base skeleton template that contains all the common elements of your site and defines blocks that child templates can override.
Find a complete guide about template inheritance in a dedicated article.
## Template
Templates are comparable with **functions** in regular programming languages.

A template is defined via the `{template}` tag. Instead of writing a plugin that generates presentational content, keeping it in the template is often a more manageable choice. It also simplifies data traversal, such as deeply nested menus.

Here is a small example of a function that renders recursive menu:
```php
<!-- subtemplates.html / subtemplates.tpl -->
{template menu data tick="-" indent=""}
  {foreach $data entry}
    {$indent}{$tick} {$entry.name}<br />
 
    {if $entry.children}
      {* recursive calls are allowed which makes subtemplates
       * especially good to output trees
       *}
      {menu $entry.children $tick cat("&nbsp;&nbsp;", $indent)}
    {/if}
  {/foreach}
{/template}

{menu $menuTree ">"}
```
Templates can be defined in any template, and need to be **loaded** via the `load_templates` tag before being used:

```php
{load_templates "subtemplates.tpl"}
 
{menu $menuTree}
```