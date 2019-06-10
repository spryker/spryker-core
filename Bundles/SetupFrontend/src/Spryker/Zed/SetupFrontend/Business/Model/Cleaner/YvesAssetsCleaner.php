<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Cleaner;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\SetupFrontend\SetupFrontendConfig;
use Symfony\Component\Filesystem\Filesystem;

class YvesAssetsCleaner implements CleanerInterface
{
    /**
     * @var \Spryker\Zed\SetupFrontend\SetupFrontendConfig
     */
    protected $setupFrontendConfig;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Zed\SetupFrontend\SetupFrontendConfig $setupFrontendConfig
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        SetupFrontendConfig $setupFrontendConfig,
        Store $store
    ) {
        $this->setupFrontendConfig = $setupFrontendConfig;
        $this->store = $store;
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
            $directories = str_replace(
                '%store%',
                strtolower($this->store->getStoreName()),
                $directoryPattern
            );
        }

        return $directories;
    }
}
