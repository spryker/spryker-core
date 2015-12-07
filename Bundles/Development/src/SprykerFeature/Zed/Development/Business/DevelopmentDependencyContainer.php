<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\DevelopmentBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Development\Business\CodeStyleFixer\CodeStyleFixer;
use SprykerFeature\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;
use SprykerFeature\Zed\Development\DevelopmentConfig;

/**
 * @method DevelopmentBusiness getFactory()
 * @method DevelopmentConfig getConfig()
 */
class DevelopmentDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return CodeStyleFixer
     */
    public function createCodeStyleFixer()
    {
        return new CodeStyleFixer(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return CodeStyleSniffer
     */
    public function createCodeStyleSniffer()
    {
        return new CodeStyleSniffer(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

}
