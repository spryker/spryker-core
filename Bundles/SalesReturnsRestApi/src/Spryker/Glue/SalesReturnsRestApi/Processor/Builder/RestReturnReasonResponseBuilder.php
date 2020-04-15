<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Builder;

use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;

class RestReturnReasonResponseBuilder implements RestReturnReasonResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface
     */
    protected $returnReasonResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface $returnReasonResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ReturnReasonResourceMapperInterface $returnReasonResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->returnReasonResourceMapper = $returnReasonResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     * @param \Generated\Shared\Transfer\ReturnReasonCollectionTransfer $returnReasonCollectionTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnReasonListRestResponse(
        ReturnReasonFilterTransfer $returnReasonFilterTransfer,
        ReturnReasonCollectionTransfer $returnReasonCollectionTransfer,
        string $localeName
    ): RestResponseInterface {
        $restReturnReasonsAttributesTransfers = $this->returnReasonResourceMapper
            ->mapReturnReasonTransfersToRestReturnReasonsAttributesTransfers(
                $returnReasonCollectionTransfer->getReturnReasons(),
                $localeName
            );

        $restResponse = $this
            ->restResourceBuilder
            ->createRestResponse(
                $returnReasonCollectionTransfer->getPagination()->getNbResults(),
                $returnReasonFilterTransfer->getFilter()->getLimit() ?? 0
            );

        foreach ($restReturnReasonsAttributesTransfers as $restReturnReasonsAttributesTransfer) {
            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    SalesReturnsRestApiConfig::RESOURCE_RETURN_REASONS,
                    null,
                    $restReturnReasonsAttributesTransfer
                )
            );
        }

        return $restResponse;
    }
}
