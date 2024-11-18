<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ConfigurableBundle\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ConfigurableBundleTemplateBuilder;
use Generated\Shared\DataBuilder\ConfigurableBundleTemplateSlotBuilder;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ConfigurableBundleDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function haveConfigurableBundleTemplate(array $seed = []): ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateBuilder = new ConfigurableBundleTemplateBuilder($seed);

        return $this->getLocator()
            ->configurableBundle()
            ->facade()
            ->createConfigurableBundleTemplate($configurableBundleTemplateBuilder->build())
            ->getConfigurableBundleTemplate();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function haveConfigurableBundleTemplateSlot(array $seed = []): ConfigurableBundleTemplateSlotTransfer
    {
        $configurableBundleTemplateSlotBuilder = new ConfigurableBundleTemplateSlotBuilder($seed);

        return $this->getLocator()
            ->configurableBundle()
            ->facade()
            ->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotBuilder->build())
            ->getConfigurableBundleTemplateSlot();
    }
}
