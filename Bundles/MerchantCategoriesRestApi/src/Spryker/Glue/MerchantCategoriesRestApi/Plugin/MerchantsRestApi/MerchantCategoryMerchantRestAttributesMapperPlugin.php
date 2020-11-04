<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantCategoriesRestApi\Plugin\MerchantsRestApi;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\MerchantRestAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\MerchantCategoriesRestApi\MerchantCategoriesRestApiFactory getFactory()
 */
class MerchantCategoryMerchantRestAttributesMapperPlugin extends AbstractPlugin implements MerchantRestAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
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
        return $this->getFactory()
            ->createMerchantCategoryMapper()
            ->mapCategoryTransfersToRestMerchantsAttributesTransfer(
                $merchantStorageTransfer->getCategories()->getArrayCopy(),
                $restMerchantsAttributesTransfer,
                $localeName
            );
    }
}
