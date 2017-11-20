<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\NameGenerator;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;

class ProductAbstractNameGenerator implements ProductAbstractNameGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductAbstractName(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer)
    {
        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            if ($localizedAttribute->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttribute->getName();
            }
        }

        return $productAbstractTransfer->getSku();
    }
}
