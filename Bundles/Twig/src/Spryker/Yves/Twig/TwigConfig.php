<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class TwigConfig extends AbstractBundleConfig
{

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
        $themeName = $this->getThemeName();

        foreach ($namespaces as $namespace) {
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Yves/%s' . $storeName . '/Theme/' . $themeName;
            $paths[] = APPLICATION_SOURCE_DIR . '/' . $namespace . '/Yves/%s/Theme/' . $themeName;
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
        $themeName = $this->getThemeName();

        foreach ($namespaces as $namespace) {
            $paths[] = APPLICATION_VENDOR_DIR . '/*/*/src/' . $namespace . '/Yves/%s/Theme/' . $themeName;
        }

        return $paths;
    }

    /**
     * @return string
     */
    protected function getThemeName()
    {
        return $this->get(TwigConstants::YVES_THEME);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }

}
