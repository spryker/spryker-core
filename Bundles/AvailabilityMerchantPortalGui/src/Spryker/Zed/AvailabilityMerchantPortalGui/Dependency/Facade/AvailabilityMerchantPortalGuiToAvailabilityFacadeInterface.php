<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityCollectionTransfer;

interface AvailabilityMerchantPortalGuiToAvailabilityFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityCollectionTransfer
     */
    public function getProductConcreteAvailabilityCollection(
        ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
    ): ProductConcreteAvailabilityCollectionTransfer;
}
