<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library;

use Exception;
use Spryker\Shared\Kernel\Store;

/**
 * Performance Optimization:
 *  Cache the information if the directory exists
 */
class DataDirectory
{

    const PATH_DATA = 'data';
    const PATH_SHARED = 'static';
    const PATH_COMMON = 'common';

    /**
     * This directory is local host only and not store specific
     *
     * @param string|null $relativePath relative path in data directory
     *
     * @return string
     */
    public static function getLocalCommonPath($relativePath = null)
    {
        $path = self::getBaseDataPath(false, false);
        $path .= DIRECTORY_SEPARATOR . self::PATH_COMMON;
        $path = self::addRelativePath($path, $relativePath);

        return self::createDirectoryIfNotExisting($path);
    }

    /**
     * This directory is local host only but store specific
     *
     * @param string|null $relativePath relative path in data directory
     *
     * @return string
     */
    public static function getLocalStoreSpecificPath($relativePath = null)
    {
        $path = self::getBaseDataPath(true, false);
        $path = self::addRelativePath($path, $relativePath);

        return self::createDirectoryIfNotExisting($path);
    }

    /**
     * This directory is shared between hosts (NFS) but not store specific
     *
     * @param string|null $relativePath relative path in data directory
     *
     * @return string
     */
    public static function getSharedCommonPath($relativePath = null)
    {
        $path = self::getBaseDataPath(false, true);
        $path .= DIRECTORY_SEPARATOR . self::PATH_COMMON;
        $path = self::addRelativePath($path, $relativePath);

        if (Cloud::isCloudStorageEnabled()) {
            return $path;
        }

        return self::createDirectoryIfNotExisting($path);
    }

    /**
     * This directory is shared between hosts (NFS) and is store specific
     *
     * @param string|null $relativePath relative path in data directory
     *
     * @return string
     */
    public static function getSharedStoreSpecificPath($relativePath = null)
    {
        $path = self::getBaseDataPath(true, true);
        $path = self::addRelativePath($path, $relativePath);

        if (Cloud::isCloudStorageEnabled()) {
            return $path;
        }

        return self::createDirectoryIfNotExisting($path);
    }

    /**
     * @param bool $storeSpecific (stored in store specific sub.folder?)
     * @param bool $isSharedBetweenHosts (weather to store in 'static' directory in shared NFS)
     *
     * @return string
     */
    protected static function getBaseDataPath($storeSpecific = false, $isSharedBetweenHosts = true)
    {
        $path = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . self::PATH_DATA;
        if ($isSharedBetweenHosts) {
            $path .= DIRECTORY_SEPARATOR . self::PATH_SHARED;
        }
        if ($storeSpecific) {
            $path .= DIRECTORY_SEPARATOR . Store::getInstance()->getStoreName();
        }

        return $path;
    }

    /**
     * @param string $path
     * @param string $relativePath
     *
     * @return string
     */
    protected static function addRelativePath($path, $relativePath)
    {
        if ($path !== null && $relativePath !== '/') {
            $path .= DIRECTORY_SEPARATOR . $relativePath;
        }
        if (substr($path, -1) !== DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }

        return $path;
    }

    /**
     * @param string $path
     *
     * @throws \Exception
     *
     * @return string
     */
    protected static function createDirectoryIfNotExisting($path)
    {
        if (is_dir($path) === false) {
            try {
                mkdir($path, 0775, true);
            } catch (\ErrorException $e) {
                throw new Exception('Could not create data directory "' . $path . '"!');
            }
        }
        if (is_writable($path) === false) {
            throw new Exception('Data directory not writable! (' . $path . ')');
        }

        return $path;
    }

}
