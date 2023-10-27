<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Mapper;

use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;

class ProductOfferServiceMapper implements ProductOfferServiceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     * @param \Generated\Shared\Transfer\ServiceCriteriaTransfer $serviceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCriteriaTransfer
     */
    public function mapIterableProductOfferServicesCriteriaTransferToServiceCriteriaTransfer(
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer,
        ServiceCriteriaTransfer $serviceCriteriaTransfer
    ): ServiceCriteriaTransfer {
        $iterableProductOfferServicesConditionsTransfer = $iterableProductOfferServicesCriteriaTransfer->getIterableProductOfferServicesConditionsOrFail();

        return (new ServiceCriteriaTransfer())->setServiceConditions(
            (new ServiceConditionsTransfer())
                ->setServiceIds($iterableProductOfferServicesConditionsTransfer->getServiceIds())
                ->setIsActive($iterableProductOfferServicesConditionsTransfer->getIsActiveService())
                ->setIsActiveServicePoint($iterableProductOfferServicesConditionsTransfer->getIsActiveServicePoint())
                ->setWithServicePointRelations($iterableProductOfferServicesConditionsTransfer->getWithServicePointRelations()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaTransfer
     */
    public function mapIterableProductOfferServicesCriteriaTransferToProductOfferCriteriaTransfer(
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer,
        ProductOfferCriteriaTransfer $productOfferCriteriaTransfer
    ): ProductOfferCriteriaTransfer {
        $iterableProductOfferServicesConditionsTransfer = $iterableProductOfferServicesCriteriaTransfer->getIterableProductOfferServicesConditionsOrFail();

        return $productOfferCriteriaTransfer
            ->setProductOfferConditions((new ProductOfferConditionsTransfer())->setProductOfferIds($iterableProductOfferServicesConditionsTransfer->getProductOfferIds()))
            ->setIsActive($iterableProductOfferServicesConditionsTransfer->getIsActiveProductOffer())
            ->setIsActiveConcreteProduct($iterableProductOfferServicesConditionsTransfer->getIsActiveConcreteProduct())
            ->setApprovalStatuses($iterableProductOfferServicesConditionsTransfer->getProductOfferApprovalStatuses());
    }
}
