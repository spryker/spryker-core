<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;

interface ProductOfferServicePointMerchantPortalGuiToServicePointFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointCriteriaTransfer $servicePointCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    public function getServicePointCollection(ServicePointCriteriaTransfer $servicePointCriteriaTransfer): ServicePointCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServiceCriteriaTransfer $serviceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollection(
        ServiceCriteriaTransfer $serviceCriteriaTransfer
    ): ServiceCollectionTransfer;
}
