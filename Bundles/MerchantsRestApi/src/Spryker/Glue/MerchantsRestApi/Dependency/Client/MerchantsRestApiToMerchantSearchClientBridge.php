<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Dependency\Client;

use Generated\Shared\Transfer\MerchantSearchRequestTransfer;

class MerchantsRestApiToMerchantSearchClientBridge implements MerchantsRestApiToMerchantSearchClientInterface
{
    /**
     * @var \Spryker\Client\MerchantSearch\MerchantSearchClientInterface
     */
    protected $merchantSearchClient;

    /**
     * @param \Spryker\Client\MerchantSearch\MerchantSearchClientInterface $merchantSearchClient
     */
    public function __construct($merchantSearchClient)
    {
        $this->merchantSearchClient = $merchantSearchClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchRequestTransfer $merchantSearchRequestTransfer
     *
     * @return array
     */
    public function merchantSearch(MerchantSearchRequestTransfer $merchantSearchRequestTransfer): array
    {
        return $this->merchantSearchClient->merchantSearch($merchantSearchRequestTransfer);
    }
}
