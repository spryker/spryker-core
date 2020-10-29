<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantCategoriesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\RestMerchantCategoryAttributesTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;

class MerchantCategoryMapper implements MerchantCategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer[] $categoryTransfers
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapCategoryTransfersToRestMerchantsAttributesTransfer(
        array $categoryTransfers,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        $restMerchantsCategoryAttributesTransfers = [];

        foreach ($categoryTransfers as $categoryTransfer) {
            $restMerchantsCategoryAttributesTransfer = (new RestMerchantCategoryAttributesTransfer())
                ->fromArray($categoryTransfer->toArray(), true);

            /**
             * @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
             */
            $categoryLocalizedAttributesTransfer = $this->findLocalizedAttributesByLocaleName($categoryTransfer, $localeName);

            $restMerchantsCategoryAttributesTransfer->setName($categoryLocalizedAttributesTransfer->getName());

            $restMerchantsCategoryAttributesTransfers[] = $restMerchantsCategoryAttributesTransfer;
        }

        return $restMerchantsAttributesTransfer->setCategories(new ArrayObject($restMerchantsCategoryAttributesTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer|null
     */
    protected function findLocalizedAttributesByLocaleName(CategoryTransfer $categoryTransfer, string $localeName): ?CategoryLocalizedAttributesTransfer
    {
        foreach ($categoryTransfer->getLocalizedAttributes() as $categoryLocalizedAttributesTransfer) {
            /**
             * @var \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
             */
            $localeTransfer = $categoryLocalizedAttributesTransfer->getLocale();

            if ($localeTransfer->getLocaleName() === $localeName) {
                return $categoryLocalizedAttributesTransfer;
            }
        }

        return null;
    }
}
