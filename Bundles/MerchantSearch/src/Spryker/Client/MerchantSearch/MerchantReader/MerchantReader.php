<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\MerchantReader;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToStoreClientInterface;
use Spryker\Client\MerchantSearch\Zed\MerchantSearchStubInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Client\MerchantSearch\Zed\MerchantSearchStubInterface
     */
    protected $merchantSearchStub;

    /**
     * @var \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\MerchantSearch\Zed\MerchantSearchStubInterface $merchantSearchStub
     * @param \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToStoreClientInterface $storeClient
     */
    public function __construct(
        MerchantSearchStubInterface $merchantSearchStub,
        MerchantSearchToStoreClientInterface $storeClient
    ) {
        $this->merchantSearchStub = $merchantSearchStub;
        $this->storeClient = $storeClient;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function get(): MerchantCollectionTransfer
    {
        return $this->merchantSearchStub->get(
            (new MerchantCriteriaTransfer())
                ->setIsActive(true)
                ->setStore($this->storeClient->getCurrentStore())
        );
    }
}
