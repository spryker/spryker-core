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
    public function getByMerchantReferences(array $merchantReferences): array
    {
        // TODO: Replace implementation with:
        // return $this->merchantStorageClient->getByMerchantReferences($merchantReferences);

        $result = [];
        foreach ($merchantReferences as $merchantReference) {
            $result[] = (new MerchantStorageTransfer())->setMerchantReference($merchantReference)
                ->setName('Name of ' . $merchantReference);
        }

        return $result;
    }
}
