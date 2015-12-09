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
        $this->getDependencyContainer()->createCodeStyleSniffer()->checkCodeStyle($bundle, $options);
    }

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function runTest($bundle, array $options = [])
    {
        $this->getDependencyContainer()->createCodeTester()->runTest($bundle, $options);
    }

}
