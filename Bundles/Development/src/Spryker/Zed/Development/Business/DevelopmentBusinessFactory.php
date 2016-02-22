<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

use Spryker\Zed\Development\Business\CodeBuilder\Bridge\BridgeBuilder;
use Spryker\Zed\Development\Business\PhpMd\PhpMdRunner;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Development\Business\CodeStyleFixer\CodeStyleFixer;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;
use Spryker\Zed\Development\Business\CodeTest\CodeTester;

/**
 * @method \Spryker\Zed\Development\DevelopmentConfig getConfig()
 */
class DevelopmentBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Development\Business\CodeStyleFixer\CodeStyleFixer
     */
    public function createCodeStyleFixer()
    {
        return new CodeStyleFixer(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer
     */
    public function createCodeStyleSniffer()
    {
        return new CodeStyleSniffer(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory(),
            $this->getConfig()->getCodingStandard()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeTest\CodeTester
     */
    public function createCodeTester()
    {
        return new CodeTester(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\PhpMd\PhpMdRunner
     */
    public function createPhpMdRunner()
    {
        return new PhpMdRunner(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeBuilder\Bridge\BridgeBuilder
     */
    public function createBridgeBuilder()
    {
        return new BridgeBuilder(
            $this->getConfig()->getBundleDirectory()
        );
    }

}
