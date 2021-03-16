<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer;

use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm;
use Symfony\Component\Form\DataTransformerInterface;

class ProductConcreteEditFormDataTransformer implements DataTransformerInterface
{
    /**
     * @param mixed[] $productConcreteEditFormData
     *
     * @return mixed[]
     */
    public function transform($productConcreteEditFormData)
    {
        $productConcreteEditFormData[ProductConcreteEditForm::FIELD_SEARCHABILITY] = [];

        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $productConcreteEditFormData[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE];

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            if ($localizedAttributesTransfer->getIsSearchable()) {
                $idLocale = $localizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail();
                $productConcreteEditFormData[ProductConcreteEditForm::FIELD_SEARCHABILITY][] = $idLocale;
            }
        }

        return $productConcreteEditFormData;
    }

    /**
     * @param mixed[] $productConcreteEditFormData
     *
     * @return mixed[]
     */
    public function reverseTransform($productConcreteEditFormData)
    {
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $productConcreteEditFormData[ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE];

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $idLocale = $localizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail();
            $localizedAttributesTransfer->setIsSearchable(
                in_array($idLocale, $productConcreteEditFormData[ProductConcreteEditForm::FIELD_SEARCHABILITY])
            );
        }

        return $productConcreteEditFormData;
    }
}
