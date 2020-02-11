<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class RestResponseBuilder implements RestResponseBuilderInterface
{
    protected const STATUS = 'status';
    protected const DETAIL = 'detail';

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
    public function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param string[] $errorCodes
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildErrorRestResponseBasedOnErrorCodes(array $errorCodes): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errorCodes as $errorCode) {
            $restResponse->addError($this->createRestErrorMessageByErrorCode($errorCode));
        }

        return $restResponse;
    }

    /**
     * @param string $errorCode
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createRestErrorMessageByErrorCode(string $errorCode): RestErrorMessageTransfer
    {
        $errorSignature = ShoppingListsRestApiConfig::RESPONSE_ERROR_MAP[$errorCode] ??
            [
                static::STATUS => ShoppingListsRestApiConfig::RESPONSE_UNEXPECTED_HTTP_STATUS,
                static::DETAIL => $errorCode,
            ];

        return (new RestErrorMessageTransfer())
            ->setCode($errorCode)
            ->setStatus($errorSignature[static::STATUS])
            ->setDetail($errorSignature[static::DETAIL]);
    }
}
