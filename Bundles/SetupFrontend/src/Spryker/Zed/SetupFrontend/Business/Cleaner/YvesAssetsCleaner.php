<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Cleaner;

use Spryker\Zed\SetupFrontend\Business\Model\Cleaner\CleanerInterface;
use Spryker\Zed\SetupFrontend\SetupFrontendConfig;
use Symfony\Component\Filesystem\Filesystem;

class YvesAssetsCleaner implements CleanerInterface
{
    protected const STORE_KEY = '%store%';

    /**
     * @var \Spryker\Zed\SetupFrontend\SetupFrontendConfig
     */
    protected $setupFrontendConfig;

    /**
     * @var string
     */
    protected $storeName;

    /**
     * @param \Spryker\Zed\SetupFrontend\SetupFrontendConfig $setupFrontendConfig
     * @param string $storeName
     */
    public function __construct(
        SetupFrontendConfig $setupFrontendConfig,
        string $storeName
    ) {
        $this->setupFrontendConfig = $setupFrontendConfig;
        $this->storeName = $storeName;
    }

    /**
     * @return bool
     */
    public function clean(): bool
    {
        $directories = $this->getDirectories();

        $filesystem = new Filesystem();
        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                $filesystem->remove($directory);
            }
        }

        return true;
    }

    /**
     * @return string[]
     */
    protected function getDirectories(): array
    {
        $directories = [];

        foreach ($this->setupFrontendConfig->getYvesAssetsDirectories() as $directoryPattern) {
            $directories[] = str_replace(
                static::STORE_KEY,
                strtolower($this->storeName),
                $directoryPattern
            );
        }

        return $directories;
    }
}
