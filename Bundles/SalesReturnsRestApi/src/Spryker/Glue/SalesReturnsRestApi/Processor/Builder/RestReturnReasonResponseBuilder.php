<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Builder;

use Generated\Shared\Transfer\ReturnReasonSearchCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer;
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
     * @param \Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer
     * @param \Generated\Shared\Transfer\ReturnReasonSearchCollectionTransfer $returnReasonSearchCollectionTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnReasonListRestResponse(
        ReturnReasonSearchRequestTransfer $returnReasonSearchRequestTransfer,
        ReturnReasonSearchCollectionTransfer $returnReasonSearchCollectionTransfer,
        string $localeName
    ): RestResponseInterface {
        $restReturnReasonsAttributesTransfers = $this->returnReasonResourceMapper
            ->mapReturnReasonSearchTransfersToRestReturnReasonsAttributesTransfers(
                $returnReasonSearchCollectionTransfer->getReturnReasons(),
                $localeName
            );

        $restResponse = $this->restResourceBuilder->createRestResponse(
            $returnReasonSearchCollectionTransfer->getNbResults(),
            $returnReasonSearchRequestTransfer->getRequestParameters()['ipp'] ?? 0
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
