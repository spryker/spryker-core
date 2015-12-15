<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Category\Code\Storage;

use Spryker\Shared\Library\Storage\StorageKeyGenerator as BaseStorageKeyGenerator;

class StorageKeyGenerator extends BaseStorageKeyGenerator
{

    const KEY_NAMESPACE = 'category';

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

}
