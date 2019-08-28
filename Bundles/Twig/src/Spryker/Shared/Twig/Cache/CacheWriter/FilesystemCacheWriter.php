<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Cache\CacheWriter;

use ErrorException;
use Spryker\Shared\Twig\Cache\CacheWriterInterface;
use Spryker\Shared\Twig\Exception\DirectoryCreationException;

class FilesystemCacheWriter implements CacheWriterInterface
{
    /**
     * @var string
     */
    protected $cacheFilePath;

    /**
     * @var int
     */
    protected $permissionMode;

    /**
     * @param string $cacheFilePath
     * @param int $permissionMode
     */
    public function __construct(string $cacheFilePath, int $permissionMode)
    {
        $this->cacheFilePath = $cacheFilePath;
        $this->permissionMode = $permissionMode;
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function write(array $data)
    {
        $cacheFileContent = '<?php return [' . PHP_EOL;
        foreach ($data as $key => $value) {
            $cacheFileContent .= '    \'' . $key . '\' => ' . var_export($value, true) . ',' . PHP_EOL;
        }
        $cacheFileContent .= '];' . PHP_EOL;

        $directory = dirname($this->cacheFilePath);
        if (!is_dir($directory)) {
            try {
                mkdir($directory, $this->permissionMode, true);
            } catch (ErrorException $exception) {
                $this->throwDirectoryCreationException($directory, $exception);
            }
        }

        file_put_contents($this->cacheFilePath, $cacheFileContent);

        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($this->cacheFilePath, true);
        }
    }

    /**
     * @param string $directory
     * @param \ErrorException $exception
     *
     * @throws \Spryker\Shared\Twig\Exception\DirectoryCreationException
     *
     * @return void
     */
    protected function throwDirectoryCreationException(string $directory, ErrorException $exception): void
    {
        throw new DirectoryCreationException(
            sprintf('Couldn\'t create directory "%s".', $directory),
            $exception->getCode(),
            $exception
        );
    }
}
