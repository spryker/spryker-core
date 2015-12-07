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
     * @param array $options
     *
     * @return void
     */
    public function fixCodeStyle($bundle = null, array $options = [])
    {
        $this->getDependencyContainer()->createCodeStyleFixer()->fixCodeStyle($bundle, $options);
    }

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function checkCodeStyle($bundle = null, array $options = [])
    {
        $this->getDependencyContainer()->createCodeStyleSniffer()->checkBundleCodeStyle($bundle, $options);
    }

}
