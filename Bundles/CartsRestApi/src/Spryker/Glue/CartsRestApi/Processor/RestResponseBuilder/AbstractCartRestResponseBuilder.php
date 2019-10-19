<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

abstract class AbstractCartRestResponseBuilder implements BaseCartRestResponseBuilderInterface
{
    protected const PATTERN_GUEST_CART_ITEM_RESOURCE_SELF_LINK = '%s/%s/%s/%s';
    protected const KEY_REST_RESOURCE_SELF_LINK = 'self';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsMapperInterface
     */
    protected $cartsMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsMapperInterface
     */
    protected $cartItemsMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsMapperInterface $cartsMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsMapperInterface $cartItemsMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CartsMapperInterface $cartsMapper,
        CartItemsMapperInterface $cartItemsMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cartsMapper = $cartsMapper;
        $this->cartItemsMapper = $cartItemsMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer[]|\ArrayObject $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedErrorResponse(ArrayObject $errors): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $quoteErrorTransfer) {
            $restResponse->addError(
                $this->cartsMapper->mapQuoteErrorTransferToRestErrorMessageTransfer(
                    $quoteErrorTransfer,
                    new RestErrorMessageTransfer()
                )
            );
        }

        return $restResponse;
    }
}
