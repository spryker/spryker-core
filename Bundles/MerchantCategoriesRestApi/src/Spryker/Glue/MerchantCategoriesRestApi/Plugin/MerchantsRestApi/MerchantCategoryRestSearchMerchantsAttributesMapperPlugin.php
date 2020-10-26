<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantCategoriesRestApi\Plugin\MerchantsRestApi;

use Generated\Shared\Transfer\MerchantSearchTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestSearchMerchantsAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\MerchantCategoriesRestApi\MerchantCategoriesRestApiFactory getFactory()
 */
class MerchantCategoryRestSearchMerchantsAttributesMapperPlugin extends AbstractPlugin implements RestSearchMerchantsAttributesMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapMerchantSearchTransferToRestMerchantsAttributesTransfer(
        MerchantSearchTransfer $merchantSearchTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        return $this->getFactory()
            ->createMerchantCategoryMapper()
            ->mapCategoryTransfersToRestMerchantsAttributesTransfer(
                $merchantSearchTransfer->getCategories()->getArrayCopy(),
                $restMerchantsAttributesTransfer,
                $localeName
            );
    }
}
