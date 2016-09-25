<?php
/**
 * Copyright (c) 2013-2016
 *
 * @category  Library
 * @package   Dwoo\Plugins\Functions
 * @author    Jordi Boggiano <j.boggiano@seld.be>
 * @author    David Sanchez <david38sanchez@gmail.com>
 * @copyright 2008-2013 Jordi Boggiano
 * @copyright 2013-2016 David Sanchez
 * @license   http://dwoo.org/LICENSE Modified BSD License
 * @version   1.3.0
 * @date      2016-09-19
 * @link      http://dwoo.org/
 */

namespace Dwoo\Plugins\Functions;

use Dwoo\Compiler;

/**
 * Replaces all white-space characters with the given string
 * <pre>
 *  * value : the text to process
 *  * with : the replacement string, note that any number of consecutive white-space characters will be replaced by a
 *  single replacement string
 * </pre>
 * Example :.
 * <code>
 * {"a    b  c		d
 *
 * e"|whitespace}
 * results in : a b c d e
 * </code>
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 */
function PluginWhitespaceCompile(Compiler $compiler, $value, $with = ' ')
{
    return "preg_replace('#\s+#'.(strcasecmp(\$this->charset, 'utf-8')===0?'u':''), $with, $value)";
}
