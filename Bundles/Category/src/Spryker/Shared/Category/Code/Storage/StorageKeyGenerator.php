<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Category\Code\Storage;

use Spryker\Shared\Kernel\Store;

/**
 * @deprecated Will be removed with next major release
 */
class StorageKeyGenerator
{
    public const KEY_SEPARATOR = '.';
    public const KEY_NAMESPACE = 'category';

    /**
     * @param string $categoryId
     *
     * @return string
     */
    public static function getCategoryKey($categoryId)
    {
        $key = implode(self::KEY_SEPARATOR, [self::KEY_NAMESPACE, 'tree', $categoryId]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @return string
     */
    public static function getCategoryTreeKey()
    {
        $key = implode(self::KEY_SEPARATOR, [self::KEY_NAMESPACE, 'tree']);

        return self::prependStoreName($key);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public static function getCategoryUrlKey($url)
    {
        $key = implode(self::KEY_SEPARATOR, [self::KEY_NAMESPACE, 'url', $url]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @param string $key
     *
     * @return string
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
     */
    protected static function prependStoreName($key)
    {
        $storeName = Store::getInstance()->getStoreName();

        return $storeName . self::KEY_SEPARATOR . $key;
    }
}
