<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Indexer;

use Generated\Shared\Transfer\ServiceCollectionTransfer;

class ServiceIndexer implements ServiceIndexerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ServiceTransfer>
     */
    public function getServiceTransfersIndexedByIdService(ServiceCollectionTransfer $serviceCollectionTransfer): array
    {
        $serviceTransfersIndexedByIdService = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $serviceTransfersIndexedByIdService[$serviceTransfer->getIdServiceOrFail()] = $serviceTransfer;
        }

        return $serviceTransfersIndexedByIdService;
    }
}
