<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Sanitize;

use Spryker\Shared\UtilSanitize\Html AS UtilSanitizeHtml;

/**
 * @deprecated use \Spryker\Zed\UtilSanitize\Business\UtilSanitizeFacade instead
 */
class Html
{

    /**
     * @var \Spryker\Shared\UtilSanitize\Html
     */
    protected static $utilSanitizeHtml;

    /**
     * Convenience method for htmlspecialchars to use UTF8 by default.
     *
     * @param string|array|object $text Text to wrap through htmlspecialchars. Also works with arrays, and objects.
     *   Arrays will be mapped and have all their elements escaped. Objects will be string cast if they
     *   implement a `__toString` method. Otherwise the class name will be used.
     * @param bool $double Encode existing html entities.
     * @param string|null $charset Character set to use when escaping. Defaults to config value in `mb_internal_encoding()`
     *   or 'UTF-8'.
     *
     * @return string Wrapped text.
     */
    public static function escape($text, $double = true, $charset = null)
    {
        return static::createUtilSanitizeHtml()->escape($text, $double, $charset);
    }

    /**
     * @return \Spryker\Shared\UtilSanitize\Html
     */
    protected static function createUtilSanitizeHtml()
    {
        if (static::$utilSanitizeHtml === null) {
            static::$utilSanitizeHtml = new UtilSanitizeHtml();
        }

        return static::$utilSanitizeHtml;
    }

}
