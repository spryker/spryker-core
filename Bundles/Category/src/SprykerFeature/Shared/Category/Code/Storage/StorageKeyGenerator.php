<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Category\Code\Storage;

use SprykerFeature\Shared\Library\Storage\StorageKeyGenerator as BaseStorageKeyGenerator;

class StorageKeyGenerator extends BaseStorageKeyGenerator
{

    const KEY_NAMESPACE = 'category';

    /**
     * @param string $categoryId
     *
     * @return string
     * @static
     */
    public static function getCategoryKey($categoryId)
    {
        $key = implode(self::KEY_SEPERATOR, [self::KEY_NAMESPACE, 'tree', $categoryId]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @return string
     * @static
     */
    public static function getCategoryTreeKey()
    {
        $key = implode(self::KEY_SEPERATOR, [self::KEY_NAMESPACE, 'tree']);

        return self::prependStoreName($key);
    }

    /**
     * @param string $url
     *
     * @return string
     * @static
     */
    public static function getCategoryUrlKey($url)
    {
        $key = implode(self::KEY_SEPERATOR, [self::KEY_NAMESPACE, 'url', $url]);

        return self::prependStoreName(self::escapeKey($key));
    }

}
