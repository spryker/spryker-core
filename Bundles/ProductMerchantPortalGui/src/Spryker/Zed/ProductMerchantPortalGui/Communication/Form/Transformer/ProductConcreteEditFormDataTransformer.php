<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class ProductConcreteEditFormDataTransformer implements DataTransformerInterface
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_SEARCHABILITY
     *
     * @var string
     */
    protected const FIELD_SEARCHABILITY = 'searchability';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_PRODUCT_CONCRETE
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE = 'productConcrete';

    /**
     * @param array<mixed> $productConcreteEditFormData
     *
     * @return array<mixed>
     */
    public function transform($productConcreteEditFormData)
    {
        $productConcreteEditFormData[static::FIELD_SEARCHABILITY] = [];

        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $productConcreteEditFormData[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE];

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            if ($localizedAttributesTransfer->getIsSearchable()) {
                $idLocale = $localizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail();
                $productConcreteEditFormData[static::FIELD_SEARCHABILITY][] = $idLocale;
            }
        }

        return $productConcreteEditFormData;
    }

    /**
     * @param array<mixed> $productConcreteEditFormData
     *
     * @return array<mixed>
     */
    public function reverseTransform($productConcreteEditFormData)
    {
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $productConcreteEditFormData[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_PRODUCT_CONCRETE];

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $idLocale = $localizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail();
            $localizedAttributesTransfer->setIsSearchable(
                in_array($idLocale, $productConcreteEditFormData[static::FIELD_SEARCHABILITY]),
            );
        }

        return $productConcreteEditFormData;
    }
}
