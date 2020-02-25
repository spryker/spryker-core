<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class RestResponseBuilder implements RestResponseBuilderInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ShoppingListsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string[] $errorIdentifiers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildErrorRestResponse(RestRequestInterface $restRequest, array $errorIdentifiers): RestResponseInterface
    {
        $restResponse = $this->createRestResponse();

        $errorIdentifiersForTranslation = [];
        foreach ($errorIdentifiers as $index => $errorIdentifier) {
            $errorIdentifierMapping = ShoppingListsRestApiConfig::getErrorIdentifierToRestErrorMapping()[$errorIdentifier] ?? null;
            if ($errorIdentifierMapping) {
                $restResponse->addError((new RestErrorMessageTransfer())->fromArray($errorIdentifierMapping, true));

                continue;
            }

            $errorIdentifiersForTranslation[] = $errorIdentifier;
        }

        $translatedErrors = $this->glossaryStorageClient->translateBulk(
            $errorIdentifiersForTranslation,
            $restRequest->getMetadata()->getLocale()
        );

        foreach ($translatedErrors as $translatedError) {
            $sku = $this->findSku($restRequest->getResource());
            $restResponse->addError($this->createErrorMessageTransfer($translatedError, $sku));
        }

        return $restResponse;
    }

    /**
     * @param string $errorMessage
     * @param string|null $sku
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(string $errorMessage, ?string $sku): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(ShoppingListsRestApiConfig::RESPONSE_CODE_VALIDATION)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(str_replace(static::MESSAGE_PARAM_SKU, $sku, $errorMessage));
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return string|null
     */
    protected function findSku(RestResourceInterface $restResource): ?string
    {
        $attributes = $restResource->getAttributes();
        if (!$attributes) {
            return null;
        }

        return $attributes->offsetExists(static::KEY_SKU) ? $attributes->offsetGet(static::KEY_SKU) : null;
    }
}
