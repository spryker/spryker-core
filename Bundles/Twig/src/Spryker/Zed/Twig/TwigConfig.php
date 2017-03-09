<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class TwigConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getBundlesDirectory()
    {
        return $this->get(KernelConstants::SPRYKER_ROOT);
    }

    /**
     * @return array
     */
    public function getTemplatePaths()
    {
        $paths = [];
        $paths = $this->addProjectTemplatePaths($paths);
        $paths = $this->addCoreTemplatePaths($paths);

        return $paths;
    }

    /**
     * @param array $paths
     *
     * @return array
     */
    protected function addProjectTemplatePaths(array $paths)
    {
        $namespaces = $this->get(KernelConstants::PROJECT_NAMESPACES);
        $storeName = $this->getStoreName();

        foreach ($namespaces as $namespace) {
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s' . $storeName . '/Presentation/';
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Zed/%s/Presentation/';
        }

        return $paths;
    }

    /**
     * @param array $paths
     *
     * @return array
     */
    protected function addCoreTemplatePaths(array $paths)
    {
        $namespaces = $this->get(KernelConstants::CORE_NAMESPACES);
        $storeName = $this->getStoreName();

        foreach ($namespaces as $namespace) {
            $paths[] = APPLICATION_VENDOR_DIR . '/*/*/src/' . $namespace . '/Zed/%s' . $storeName . '/Presentation/';
            $paths[] = APPLICATION_VENDOR_DIR . '/*/*/src/' . $namespace . '/Zed/%s/Presentation/';
        }

        return $paths;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }

}
