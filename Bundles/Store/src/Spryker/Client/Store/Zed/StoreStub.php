<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store\Zed;

use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Spryker\Client\Store\Dependency\Client\StoreToZedRequestClientInterface;

class StoreStub implements StoreStubInterface
{
    /**
     * @var string
     */
    protected const STORE_GATEWAY_GET_STORE_COLLECTION = '/store/gateway/get-store-collection';

    /**
     * @var \Spryker\Client\Store\Dependency\Client\StoreToZedRequestClientInterface
     */
    protected StoreToZedRequestClientInterface $zedRequestClient;

    /**
     * @param \Spryker\Client\Store\Dependency\Client\StoreToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(StoreToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\Store\Communication\Controller\GatewayController::getStoreCollectionAction()
     *
     * @param \Generated\Shared\Transfer\StoreCriteriaTransfer $storeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function getStoreCollection(StoreCriteriaTransfer $storeCriteriaTransfer): StoreCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\StoreCollectionTransfer $stores */
        $stores = $this->zedRequestClient->call(
            static::STORE_GATEWAY_GET_STORE_COLLECTION,
            $storeCriteriaTransfer,
        );

        return $stores;
    }
}
