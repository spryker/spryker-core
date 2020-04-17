<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\Zed;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientInterface;

class MerchantSearchStub implements MerchantSearchStubInterface
{
    /**
     * @var \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(MerchantSearchToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\MerchantSearch\Communication\Controller\GatewayController::getAction()
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function get(MerchantCriteriaTransfer $merchantCriteriaTransfer): MerchantCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer */
        $merchantCollectionTransfer = $this->zedRequestClient->call('/merchant-search/gateway/get', $merchantCriteriaTransfer);

        return $merchantCollectionTransfer;
    }
}
