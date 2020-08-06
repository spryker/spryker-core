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

/**
 * @deprecated Use {@link \Spryker\Shared\Kernel\KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED} instead.
 */
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
    public function persist(array $data): void
    {
        try {
            $string = var_export($data, true);

            $flag = LOCK_EX | LOCK_NB;
            if (Config::get(KernelConstants::AUTO_LOADER_CACHE_FILE_NO_LOCK)) {
                $flag = LOCK_NB;
            }

            $cacheFilePath = $this->getCacheFilename();
            if (!is_dir(dirname($cacheFilePath))) {
                mkdir(dirname($cacheFilePath), 0755, true);
            }

            file_put_contents(
                $cacheFilePath,
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
    public function getData(): array
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
    protected function getCacheFilename(): string
    {
        if (!$this->cacheFilePath) {
            $defaultPath = APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/cache/' . ucfirst(strtolower(APPLICATION)) . '/unresolvable.cache';

            $this->cacheFilePath = Config::get(KernelConstants::AUTO_LOADER_CACHE_FILE_PATH, $defaultPath);

            $this->assertForwardCompatibility($defaultPath, $this->cacheFilePath);
        }

        return $this->cacheFilePath;
    }

    /**
     * Assert that projects have overridden the cache file path to be able to safely refactor the default path.
     *
     * @deprecated Will be removed when `ucfirst(strtolower(APPLICATION))` is replaced with `APPLICATION` in the default cache file path.
     *
     * @param string $defaultCacheFilePath
     * @param string $cacheFilePath
     *
     * @return void
     */
    protected function assertForwardCompatibility(string $defaultCacheFilePath, string $cacheFilePath): void
    {
        if ($defaultCacheFilePath === $cacheFilePath) {
            $message = 'The default cache file path will use upper case application names in the feature. '
                . 'For forward compatibility use "KernelConstants::AUTO_LOADER_CACHE_FILE_PATH" to configure the cache file path accordingly.';

            trigger_error($message, E_USER_DEPRECATED);
        }
    }
}
