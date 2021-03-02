<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Dependency\Client;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
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
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function get(MerchantCriteriaTransfer $merchantCriteriaTransfer): array
    {
        return $this->merchantStorageClient->get($merchantCriteriaTransfer);
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
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOne(MerchantCriteriaTransfer $merchantCriteriaTransfer): ?MerchantStorageTransfer
    {
        return $this->merchantStorageClient->findOne($merchantCriteriaTransfer);
    }
}
