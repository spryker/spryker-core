<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Reader;

use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface;

class MerchantStorageReader implements MerchantStorageReaderInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface
     */
    protected $merchantRestResponseBuilder;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface $merchantStorageClient
     * @param \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface $merchantRestResponseBuilder
     */
    public function __construct(
        MerchantsRestApiToMerchantStorageClientInterface $merchantStorageClient,
        MerchantRestResponseBuilderInterface $merchantRestResponseBuilder
    ) {
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantRestResponseBuilder = $merchantRestResponseBuilder;
    }

    /**
     * @param string[] $merchantReferences
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getMerchantsResourceCollection(array $merchantReferences): array
    {
        $merchantStorageTransfers = $this->merchantStorageClient->findByMerchantReference($merchantReferences);

        $indexedMerchantStorageTransfers = $this->indexByMerchantReference($merchantStorageTransfers);

        return $this->merchantRestResponseBuilder->createRestResourceCollection($indexedMerchantStorageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    protected function indexByMerchantReference(array $merchantStorageTransfers): array
    {
        $indexedMerchantStorageTransfers = [];

        foreach ($merchantStorageTransfers as $merchantStorageTransfer) {
            $indexedMerchantStorageTransfers[$merchantStorageTransfer->getMerchantReference()] = $merchantStorageTransfer;
        }

        return $indexedMerchantStorageTransfers;
    }
}
