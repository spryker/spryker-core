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

    /**
     * @param \Spryker\Client\MerchantStorage\MerchantStorageClientInterface $merchantStorageClient
     */
    public function __construct($merchantStorageClient)
    {
        $this->merchantStorageClient = $merchantStorageClient;
    }

    /**
     * @param string[] $merchantReferences
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function getByMerchantReferences(array $merchantReferences): array
    {
        return $this->merchantStorageClient->getByMerchantReferences($merchantReferences);
    }

    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOneByMerchantReference(string $merchantReference): ?MerchantStorageTransfer
    {
        return $this->merchantStorageClient->findOneByMerchantReference($merchantReference);
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOne(int $idMerchant): ?MerchantStorageTransfer
    {
        return $this->merchantStorageClient->findOne($idMerchant);
    }
}
