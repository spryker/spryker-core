<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage;

abstract class StorageKeyGenerator
{

    const KEY_SEPARATOR = '.';

    /**
     * @param string $key
     *
     * @return string
     * @static
     */
    protected static function escapeKey($key)
    {
        $charsToReplace = ['"', "'", ' ', "\0", "\n", "\r"];

        return str_replace($charsToReplace, '-', mb_strtolower(trim($key)));
    }

    /**
     * @param string $key
     *
     * @return string
     * @static
     */
    protected static function prependStoreName($key)
    {
        $storeName = \SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName();

        return $storeName . self::KEY_SEPARATOR . $key;
    }

}
