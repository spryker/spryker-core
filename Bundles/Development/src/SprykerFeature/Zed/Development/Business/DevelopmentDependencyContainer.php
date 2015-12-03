<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\DevelopmentBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Development\Business\CodeStyleFixer\BundleCodeStyleFixer;
use SprykerFeature\Zed\Development\DevelopmentConfig;

/**
 * @method DevelopmentBusiness getFactory()
 * @method DevelopmentConfig getConfig()
 */
class DevelopmentDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return BundleCodeStyleFixer
     */
    public function createBundleCodeStyleFixer()
    {
        return new BundleCodeStyleFixer(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

}
