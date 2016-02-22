<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class DevelopmentFacade extends AbstractFacade implements DevelopmentFacadeInterface
{

    /**
     * @param string|null $bundle
     * @param array $options
     *
     * @return int Exit code
     */
    public function checkCodeStyle($bundle = null, array $options = [])
    {
        return $this->getFactory()->createCodeStyleSniffer()->checkCodeStyle($bundle, $options);
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

    /**
     * @param string|null $bundle
     *
     * @return void
     */
    public function runPhpMd($bundle)
    {
        $this->getFactory()->createPhpMdRunner()->run($bundle);
    }

    /**
     * @param string $bundle
     * @param string $toBundle
     *
     * @return void
     */
    public function createBridge($bundle, $toBundle)
    {
        $this->getFactory()->createBridgeBuilder()->build($bundle, $toBundle);
    }

}
