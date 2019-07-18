<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Cache\Storage;

use Exception;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Shared\Kernel\ClassResolver\Cache\StorageInterface;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;

class File implements StorageInterface
{
    /**
     * @var string|null
     */
    protected $cacheFilePath;

    /**
     * @param array $data
     *
     * @return void
     */
    public function persist(array $data)
    {
        try {
            $string = var_export($data, true);

            $flag = LOCK_EX | LOCK_NB;
            if (Config::get(KernelConstants::AUTO_LOADER_CACHE_FILE_NO_LOCK)) {
                $flag = LOCK_NB;
            }

            file_put_contents(
                $this->getCacheFilename(),
                '<?php return ' . $string . ';',
                $flag
            );

            if (function_exists('opcache_invalidate')) {
                opcache_invalidate($this->getCacheFilename(), true);
            }
        } catch (Exception $exception) {
            ErrorLogger::getInstance()->log($exception);
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        try {
            $cache = include $this->getCacheFilename();

            return $cache ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * @return string
     */
    protected function getCacheFilename()
    {
        if (!$this->cacheFilePath) {
            $defaultPath = APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/cache/' . ucfirst(strtolower(APPLICATION)) . '/unresolvable.cache';

            $this->cacheFilePath = Config::get(KernelConstants::AUTO_LOADER_CACHE_FILE_PATH, $defaultPath);
        }

        return $this->cacheFilePath;
    }
}
