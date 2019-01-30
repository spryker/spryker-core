<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductsRestApi\Processor;

use Generated\Shared\Transfer\RestContentAbstractProductListAttributesTransfer;
use Generated\Shared\Transfer\RestContentAbstractProductTransfer;
use Spryker\Glue\ContentProductsRestApi\ContentProductsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ContentProductReader implements ContentProductReaderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder
    )
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getContentItemById(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restTransfer = new RestContentAbstractProductListAttributesTransfer();

        if ($restRequest->getResource()->getId() == '01') {
            $restTransfer->addAbstractProducts((new RestContentAbstractProductTransfer())
                ->setAbstractSku('005'));
        } else {
            $restTransfer->addAbstractProducts((new RestContentAbstractProductTransfer())
                ->setAbstractSku('007'));
            $restTransfer->addAbstractProducts((new RestContentAbstractProductTransfer())
                ->setAbstractSku('008'));
        }

        $restResource = $this->restResourceBuilder->createRestResource(
            ContentProductsRestApiConfig::RESOURCE_CONTENT_PRODUCTS,
            $restRequest->getResource()->getId(),
            $restTransfer
        );

        $restResponse->addResource($restResource);

        return $restResponse;
    }
}