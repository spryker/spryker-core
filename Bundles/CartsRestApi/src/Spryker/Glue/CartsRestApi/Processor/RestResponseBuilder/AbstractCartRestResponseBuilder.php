<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

abstract class AbstractCartRestResponseBuilder implements BaseCartRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CartsResourceMapperInterface $cartsResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cartsResourceMapper = $cartsResourceMapper;
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
                $this->cartsResourceMapper->mapQuoteErrorTransferToRestErrorMessageTransfer(
                    $quoteErrorTransfer,
                    new RestErrorMessageTransfer()
                )
            );
        }

        return $restResponse;
    }
}
