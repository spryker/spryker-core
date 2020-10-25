<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantCategoriesRestApi\Plugin\MerchantsRestApi;

use ArrayObject;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\RestMerchantCategoryAttributesTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;
use Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestMerchantsAttributesMapperPluginInterface;

class MerchantCategoryRestMerchantsAttributesMapperPlugin implements RestMerchantsAttributesMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapMerchantStorageTransferToRestMerchantsAttributesTransfer(
        MerchantStorageTransfer $merchantStorageTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        $restMerchantsCategoryAttributesTransfers = [];

        foreach ($merchantStorageTransfer->getCategories() as $categoryTransfer) {
            $restMerchantsCategoryAttributesTransfer = (new RestMerchantCategoryAttributesTransfer())
                ->fromArray($categoryTransfer->toArray(), true);

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
            if ($categoryLocalizedAttributesTransfer->getLocale()->getLocaleName() === $localeName) {
                return $categoryLocalizedAttributesTransfer;
            }
        }

        return null;
    }
}
