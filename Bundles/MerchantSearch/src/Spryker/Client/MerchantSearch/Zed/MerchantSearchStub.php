<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\Zed;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientInterface;

class MerchantSearchStub implements MerchantSearchStubInterface
{
    /**
     * @var \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientInterface $zedStub
     */
    public function __construct(MerchantSearchToZedRequestClientInterface $zedStub)
    {
        $this->zedRequestClient = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getActiveMerchants(MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer): MerchantCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer */
        $merchantCollectionTransfer = $this->zedRequestClient->call('/merchant-search/gateway/get-active-merchants', $merchantCriteriaFilterTransfer);

        return $merchantCollectionTransfer;
    }
}
