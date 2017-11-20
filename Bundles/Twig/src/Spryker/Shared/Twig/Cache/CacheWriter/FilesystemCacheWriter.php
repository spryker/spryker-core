<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Cache\CacheWriter;

use Spryker\Shared\Twig\Cache\CacheWriterInterface;

class FilesystemCacheWriter implements CacheWriterInterface
{
    /**
     * @var string
     */
    protected $cacheFilePath;

    /**
     * @param string $cacheFilePath
     */
    public function __construct($cacheFilePath)
    {
        $this->cacheFilePath = $cacheFilePath;
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
            mkdir($directory, 0755, true);
        }

        file_put_contents($this->cacheFilePath, $cacheFileContent);

        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($this->cacheFilePath, true);
        }
    }
}
