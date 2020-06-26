<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader;

use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ContentProductAbstractListReader implements ContentProductAbstractListReaderInterface
{
    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface
     */
    protected $contentProductClient;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface
     */
    protected $contentProductAbstractListRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface $contentProductClient
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface $contentProductAbstractListRestResponseBuilder
     */
    public function __construct(
        ContentProductAbstractListsRestApiToContentProductClientInterface $contentProductClient,
        ContentProductAbstractListRestResponseBuilderInterface $contentProductAbstractListRestResponseBuilder
    ) {
        $this->contentProductClient = $contentProductClient;
        $this->contentProductAbstractListRestResponseBuilder = $contentProductAbstractListRestResponseBuilder;
    }

    /**
     * @phpstan-return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param string[] $contentProductAbstractListKeys
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getContentProductAbstractListsResources(array $contentProductAbstractListKeys, string $localeName): array
    {
        $contentProductAbstractListTypeTransfers = $this->contentProductClient->executeProductAbstractListTypeByKeys(
            $contentProductAbstractListKeys,
            $localeName
        );

        if (!$contentProductAbstractListTypeTransfers) {
            return [];
        }

        return $this->contentProductAbstractListRestResponseBuilder
            ->createContentProductAbstractListsRestResources($contentProductAbstractListTypeTransfers);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getContentProductAbstractListsById(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getResource()->getId()) {
            return $this->contentProductAbstractListRestResponseBuilder->createContentItemIdNotSpecifiedErrorResponse();
        }

        $contentProductAbstractListsResources = $this->getContentProductAbstractListsResources(
            [$restRequest->getResource()->getId()],
            $restRequest->getMetadata()->getLocale()
        );

        if (!isset($contentProductAbstractListsResources[$restRequest->getResource()->getId()])) {
            return $this->contentProductAbstractListRestResponseBuilder->createContentItemtNotFoundErrorResponse();
        }

        return $this->contentProductAbstractListRestResponseBuilder
            ->createContentProductAbstractListRestResponse($contentProductAbstractListsResources[$restRequest->getResource()->getId()]);
    }
}
