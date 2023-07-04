<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Mapper;

use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;

interface ProductOfferServiceMapperInterface
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
    ): ServiceCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaTransfer
     */
    public function mapIterableProductOfferServicesCriteriaTransferToProductOfferCriteriaTransfer(
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer,
        ProductOfferCriteriaTransfer $productOfferCriteriaTransfer
    ): ProductOfferCriteriaTransfer;
}
