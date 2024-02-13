<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Phpstan\Config;

use InvalidArgumentException;
use Nette\DI\Config\Adapter;
use RuntimeException;

class PhpstanConfigFileSaver implements PhpstanConfigFileSaverInterface
{
    /**
     * @var string
     */
    protected const ERROR_CANNOT_WRITE_FILE = "Cannot write file '%s'.";

    /**
     * @var string
     */
    protected const ERROR_UNKNOWN_FILE_EXTENSION = "Unknown file extension '%s'.";

    /**
     * @var array<string, string>
     */
    protected array $fileAdapters;

    /**
     * @param array<string, string> $fileAdapters
     */
    public function __construct(array $fileAdapters = [])
    {
        $this->fileAdapters = $fileAdapters;
    }

    /**
     * @param string $configFilePath
     * @param array<mixed> $data
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function save(string $configFilePath, array $data): void
    {
        if (file_put_contents($configFilePath, $this->getAdapter($configFilePath)->dump($data)) === false) {
            throw new RuntimeException(sprintf(static::ERROR_CANNOT_WRITE_FILE, $configFilePath));
        }
    }

    /**
     * @param string $filePath
     *
     * @throws \InvalidArgumentException
     *
     * @return \Nette\DI\Config\Adapter
     */
    protected function getAdapter(string $filePath): Adapter
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (!isset($this->fileAdapters[$extension])) {
            throw new InvalidArgumentException(sprintf(static::ERROR_UNKNOWN_FILE_EXTENSION, $filePath));
        }

        if ($this->fileAdapters[$extension] instanceof Adapter) {
            return $this->fileAdapters[$extension];
        }

        /** @var \Nette\DI\Config\Adapter $adapter */
        $adapter = new $this->fileAdapters[$extension]();

        return $adapter;
    }
}
