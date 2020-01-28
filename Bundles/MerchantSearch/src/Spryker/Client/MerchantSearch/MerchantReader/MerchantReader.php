<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\MerchantReader;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Spryker\Client\MerchantSearch\Zed\MerchantSearchStubInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Client\MerchantSearch\Zed\MerchantSearchStubInterface
     */
    protected $merchantSearchStub;

    /**
     * @param \Spryker\Client\MerchantSearch\Zed\MerchantSearchStubInterface $merchantSearchStub
     */
    public function __construct(MerchantSearchStubInterface $merchantSearchStub)
    {
        $this->merchantSearchStub = $merchantSearchStub;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getActiveMerchants(): MerchantCollectionTransfer
    {
        return $this->merchantSearchStub->getActiveMerchants(new MerchantCriteriaFilterTransfer());
    }
}
