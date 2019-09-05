<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestUrlResolverAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\UrlsRestApi\UrlsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class UrlResponseBuilder implements UrlResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUrlRequestParamMissingErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setCode(UrlsRestApiConfig::RESPONSE_CODE_URL_REQUEST_PARAMETER_MISSING)
                    ->setDetail(UrlsRestApiConfig::RESPONSE_DETAIL_URL_REQUEST_PARAMETER_MISSING)
            );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUrlNotFoundErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setCode(UrlsRestApiConfig::RESPONSE_CODE_URL_NOT_FOUND)
                    ->setDetail(UrlsRestApiConfig::RESPONSE_DETAIL_URL_NOT_FOUND)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\RestUrlResolverAttributesTransfer $restUrlResolverAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUrlResolverResourceResponse(
        RestUrlResolverAttributesTransfer $restUrlResolverAttributesTransfer
    ): RestResponseInterface {
        return $this->restResourceBuilder->createRestResponse()->addResource(
            $this->restResourceBuilder->createRestResource(
                UrlsRestApiConfig::RESOURCE_URL_RESOLVER,
                null,
                $restUrlResolverAttributesTransfer
            )
        );
    }
}
