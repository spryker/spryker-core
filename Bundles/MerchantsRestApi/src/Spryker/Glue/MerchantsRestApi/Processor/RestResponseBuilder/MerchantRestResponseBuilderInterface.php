<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder;

interface MerchantRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $indexedMerchantStorageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createRestResourceCollection(array $indexedMerchantStorageTransfers): array;
}
