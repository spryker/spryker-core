<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantCategoriesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\MerchantCategoryStorageTransfer;
use Generated\Shared\Transfer\RestMerchantCategoryAttributesTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;

class MerchantCategoryMapper implements MerchantCategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryStorageTransfer[] $merchantCategoryStorageTransfers
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapCategoryTransfersToRestMerchantsAttributesTransfer(
        array $merchantCategoryStorageTransfers,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        $restMerchantsCategoryAttributesTransfers = [];

        foreach ($merchantCategoryStorageTransfers as $merchantCategoryStorageTransfer) {
            if (!$merchantCategoryStorageTransfer->getIsActive()) {
                continue;
            }

            $restMerchantsCategoryAttributesTransfer = (new RestMerchantCategoryAttributesTransfer())
                ->fromArray($merchantCategoryStorageTransfer->toArray(), true);

            $categoryLocalizedAttributesTransfer = $this->findLocalizedAttributesByLocaleName($merchantCategoryStorageTransfer, $localeName);
            if (!$categoryLocalizedAttributesTransfer) {
                continue;
            }

            $restMerchantsCategoryAttributesTransfer->setName($categoryLocalizedAttributesTransfer->getName());

            $restMerchantsCategoryAttributesTransfers[] = $restMerchantsCategoryAttributesTransfer;
        }

        return $restMerchantsAttributesTransfer->setCategories(new ArrayObject($restMerchantsCategoryAttributesTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryStorageTransfer $merchantCategoryStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantCategoryLocalizedAttributesTransfer|null
     */
    protected function findLocalizedAttributesByLocaleName(
        MerchantCategoryStorageTransfer $merchantCategoryStorageTransfer,
        string $localeName
    ): ?MerchantCategoryLocalizedAttributesTransfer {
        foreach ($merchantCategoryStorageTransfer->getLocalizedAttributes() as $categoryLocalizedAttributesTransfer) {
            $localeTransfer = $categoryLocalizedAttributesTransfer->getLocale();

            if ($localeTransfer && $localeTransfer->getLocaleName() === $localeName) {
                return $categoryLocalizedAttributesTransfer;
            }
        }

        return null;
    }
}
