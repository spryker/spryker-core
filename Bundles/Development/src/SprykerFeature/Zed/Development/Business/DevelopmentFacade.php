<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method DevelopmentDependencyContainer getDependencyContainer()
 */
class DevelopmentFacade extends AbstractFacade
{

    /**
     * @param string|null $bundle
     * @param bool $clear
     *
     * @return void
     */
    public function fixCodeStyle($bundle = null, $clear = false)
    {
        $this->getDependencyContainer()->createBundleCodeStyleFixer()->fixBundleCodeStyle($bundle, $clear);
    }

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function checkCodeStyle($bundle = null, array $options = [])
    {
        $this->getDependencyContainer()->createBundleCodeStyleSniffer()->checkBundleCodeStyle($bundle, $options);
    }

}
