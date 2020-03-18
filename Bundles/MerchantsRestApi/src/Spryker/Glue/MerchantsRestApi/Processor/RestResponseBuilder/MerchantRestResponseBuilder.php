<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface;

class MerchantRestResponseBuilder implements MerchantRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface
     */
    protected $merchantMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface $merchantMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        MerchantMapperInterface $merchantMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->merchantMapper = $merchantMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $indexedMerchantStorageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createRestResourceCollection(array $indexedMerchantStorageTransfers): array
    {
        $merchantRestResourceCollection = [];
        foreach ($indexedMerchantStorageTransfers as $merchantReference => $merchantStorageTransfer) {
            $merchantRestResourceCollection[$merchantReference] = $this->createMerchantRestResource($merchantStorageTransfer);
        }

        return $merchantRestResourceCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createMerchantRestResource(MerchantStorageTransfer $merchantStorageTransfer): RestResourceInterface
    {
        $restMerchantsAttributesTransfer = $this->merchantMapper
            ->mapMerchantStorageTransferToRestMerchantsAttributesTransfer(
                $merchantStorageTransfer,
                new RestMerchantsAttributesTransfer()
            );

        $restResourceId = $merchantStorageTransfer->getMerchantReference();

        return $this->restResourceBuilder->createRestResource(
            MerchantsRestApiConfig::RESOURCE_MERCHANTS,
            $restResourceId,
            $restMerchantsAttributesTransfer
        )->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLink($restResourceId)
        );
    }

    /**
     * @param string $restResourceId
     *
     * @return string
     */
    protected function createSelfLink(string $restResourceId): string
    {
        return sprintf(
            '%s/%s',
            MerchantsRestApiConfig::RESOURCE_MERCHANTS,
            $restResourceId
        );
    }
}
