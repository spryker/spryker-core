<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business\Mapper;

use Generated\Shared\Transfer\ProductOfferServiceStorageTransfer;
use Generated\Shared\Transfer\ProductOfferServicesTransfer;

class ProductOfferServiceMapper implements ProductOfferServiceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicesTransfer $productOfferServicesTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceStorageTransfer
     */
    public function mapProductOfferServicesTransferToProductOfferServiceStorageTransfer(
        ProductOfferServicesTransfer $productOfferServicesTransfer,
        ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer
    ): ProductOfferServiceStorageTransfer {
        $productOfferServiceStorageTransfer->setProductOfferReference(
            $productOfferServicesTransfer->getProductOfferOrFail()->getProductOfferReferenceOrFail(),
        );

        foreach ($productOfferServicesTransfer->getServices() as $serviceTransfer) {
            if (!$productOfferServiceStorageTransfer->getServicePointUuid()) {
                $productOfferServiceStorageTransfer->setServicePointUuid(
                    $serviceTransfer->getServicePointOrFail()->getUuidOrFail(),
                );
            }

            $productOfferServiceStorageTransfer->addServiceUuid(
                $serviceTransfer->getUuidOrFail(),
            );
        }

        return $productOfferServiceStorageTransfer;
    }
}
