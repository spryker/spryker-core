<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Reader;

use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;

interface ServiceReaderInterface
{
    /**
     * @param list<string> $serviceUuids
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByServiceUuids(array $serviceUuids): ServiceCollectionTransfer;

    /**
     * @param list<int> $serviceIds
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByServiceIds(array $serviceIds): ServiceCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServiceConditionsTransfer $serviceConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByServiceConditions(ServiceConditionsTransfer $serviceConditionsTransfer): ServiceCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByIterableProductOfferServicesCriteria(
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
    ): ServiceCollectionTransfer;
}
