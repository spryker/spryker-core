<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method DevelopmentBusinessFactory getFactory()
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
        $this->getFactory()->createCodeStyleFixer()->fixCodeStyle($bundle, $options);
    }

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function checkCodeStyle($bundle = null, array $options = [])
    {
        $this->getFactory()->createCodeStyleSniffer()->checkCodeStyle($bundle, $options);
    }

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function runTest($bundle, array $options = [])
    {
        $this->getFactory()->createCodeTester()->runTest($bundle, $options);
    }

}
