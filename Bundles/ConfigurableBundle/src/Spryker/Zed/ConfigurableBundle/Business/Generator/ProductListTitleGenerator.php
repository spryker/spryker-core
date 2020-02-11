<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Generator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;

class ProductListTitleGenerator implements ProductListTitleGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return string
     */
    public function generateProductListTitle(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): string
    {
        $configurableBundleTemplateSlotTransfer
            ->requireTranslations()
            ->requireConfigurableBundleTemplate()
            ->getConfigurableBundleTemplate()
                ->requireTranslations();

        return sprintf(
            '%s - %s',
            $configurableBundleTemplateSlotTransfer->getConfigurableBundleTemplate()->getTranslations()[0]->getName(),
            $configurableBundleTemplateSlotTransfer->getTranslations()[0]->getName()
        );
    }
}
