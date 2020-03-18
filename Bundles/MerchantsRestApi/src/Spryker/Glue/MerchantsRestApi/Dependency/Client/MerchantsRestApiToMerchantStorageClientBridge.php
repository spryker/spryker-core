<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Dependency\Client;

use Generated\Shared\Transfer\MerchantStorageTransfer;

class MerchantsRestApiToMerchantStorageClientBridge implements MerchantsRestApiToMerchantStorageClientInterface
{
    /**
     * @var \Spryker\Client\MerchantStorage\MerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    public function __construct()
    {
        // TODO add \Spryker\Client\MerchantStorage\MerchantStorageClientInterface $merchantStorageClient
        $this->merchantStorageClient = null;
    }

    /**
     * @param array $merchantReferences
     *
     * @return array
     */
    public function findByMerchantReference(array $merchantReferences): array
    {
        // TODO: Implement findByMerchantReference() method.
        $result = [];
        foreach ($merchantReferences as $merchantReference) {
            $result[] = (new MerchantStorageTransfer())->setMerchantReference($merchantReference);
        }

        return $result;
    }
}
