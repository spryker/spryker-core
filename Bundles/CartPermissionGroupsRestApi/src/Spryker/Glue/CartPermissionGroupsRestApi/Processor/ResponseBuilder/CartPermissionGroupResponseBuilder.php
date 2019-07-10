<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\RestCartPermissionGroupsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartPermissionGroupsRestApi\CartPermissionGroupsRestApiConfig;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CartPermissionGroupResponseBuilder implements CartPermissionGroupResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapperInterface
     */
    protected $cartPermissionGroupMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapperInterface $cartPermissionGroupMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        CartPermissionGroupMapperInterface $cartPermissionGroupMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->cartPermissionGroupMapper = $cartPermissionGroupMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createEmptyCartPermissionGroupsResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\QuotePermissionGroupTransfer[] $quotePermissionGroups
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartPermissionGroupsCollectionResponse(ArrayObject $quotePermissionGroups): RestResponseInterface
    {
        $restResponse = $this->createEmptyCartPermissionGroupsResponse();

        foreach ($quotePermissionGroups as $quotePermissionGroupTransfer) {
            $restResponse->addResource($this->createCartPermissionGroupsResource($quotePermissionGroupTransfer));
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartPermissionGroupsResponse(QuotePermissionGroupTransfer $quotePermissionGroupTransfer): RestResponseInterface
    {
        return $this->createEmptyCartPermissionGroupsResponse()
            ->addResource($this->createCartPermissionGroupsResource($quotePermissionGroupTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCartPermissionGroupsResource(QuotePermissionGroupTransfer $quotePermissionGroupTransfer): RestResourceInterface
    {
        $cartPermissionGroupRestResource = $this->restResourceBuilder->createRestResource(
            CartPermissionGroupsRestApiConfig::RESOURCE_CART_PERMISSION_GROUPS,
            (string)$quotePermissionGroupTransfer->getIdQuotePermissionGroup(),
            $this->cartPermissionGroupMapper->mapQuotePermissionGroupTransferToRestCartPermissionGroupsAttributesTransfer(
                $quotePermissionGroupTransfer,
                new RestCartPermissionGroupsAttributesTransfer()
            )
        );
        $cartPermissionGroupRestResource->setPayload($quotePermissionGroupTransfer);

        return $cartPermissionGroupRestResource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartPermissionGroupNotFoundErrorResponse(): RestResponseInterface
    {
        $errorMessageTransfer = $this->createErrorMessage(
            CartPermissionGroupsRestApiConfig::RESPONSE_CODE_CART_PERMISSION_GROUP_NOT_FOUND,
            Response::HTTP_NOT_FOUND,
            CartPermissionGroupsRestApiConfig::RESPONSE_DETAIL_CART_PERMISSION_GROUP_NOT_FOUND
        );

        return $this->createEmptyCartPermissionGroupsResponse()
            ->addError($errorMessageTransfer);
    }

    /**
     * @param string $code
     * @param int $status
     * @param string $detail
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessage(string $code, int $status, string $detail): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode($code)
            ->setStatus($status)
            ->setDetail($detail);
    }
}
