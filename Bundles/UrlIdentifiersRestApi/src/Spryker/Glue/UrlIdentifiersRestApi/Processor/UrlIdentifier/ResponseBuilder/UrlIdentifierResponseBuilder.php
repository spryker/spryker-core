<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\ResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestUrlIdentifiersAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\UrlIdentifiersRestApi\UrlIdentifiersRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class UrlIdentifierResponseBuilder implements UrlIdentifierResponseBuilderInterface
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
                    ->setCode(UrlIdentifiersRestApiConfig::RESPONSE_CODE_URL_REQUEST_PARAMETER_MISSING)
                    ->setDetail(UrlIdentifiersRestApiConfig::RESPONSE_DETAIL_URL_REQUEST_PARAMETER_MISSING)
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
                    ->setCode(UrlIdentifiersRestApiConfig::RESPONSE_CODE_URL_NOT_FOUND)
                    ->setDetail(UrlIdentifiersRestApiConfig::RESPONSE_DETAIL_URL_NOT_FOUND)
            );
    }

    /**
     * @param string $urlIdentifierId
     * @param \Generated\Shared\Transfer\RestUrlIdentifiersAttributesTransfer $restUrlIdentifiersAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUrlIdentifiersResourceResponse(
        string $urlIdentifierId,
        RestUrlIdentifiersAttributesTransfer $restUrlIdentifiersAttributesTransfer
    ): RestResponseInterface {
        return $this->restResourceBuilder->createRestResponse()->addResource(
            $this->restResourceBuilder->createRestResource(
                UrlIdentifiersRestApiConfig::RESOURCE_URL_IDENTIFIERS,
                $urlIdentifierId,
                $restUrlIdentifiersAttributesTransfer
            )
        );
    }
}
