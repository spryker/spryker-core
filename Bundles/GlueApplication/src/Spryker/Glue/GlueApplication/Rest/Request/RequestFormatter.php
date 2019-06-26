<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestFormatter implements RequestFormatterInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface
     */
    protected $requestMetaDataExtractor;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\RequestResourceExtractorInterface
     */
    protected $requestResourceExtractor;

    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $glueApplicationConfig;

    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatRequestPluginInterface[]
     */
    protected $requestFormatterPlugins = [];

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface $requestMetaDataExtractor
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestResourceExtractorInterface $requestResourceExtractor
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $glueApplicationConfig
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatRequestPluginInterface[] $requestFormatterPlugins
     */
    public function __construct(
        RequestMetaDataExtractorInterface $requestMetaDataExtractor,
        RequestResourceExtractorInterface $requestResourceExtractor,
        GlueApplicationConfig $glueApplicationConfig,
        array $requestFormatterPlugins = []
    ) {
        $this->requestMetaDataExtractor = $requestMetaDataExtractor;
        $this->requestResourceExtractor = $requestResourceExtractor;
        $this->glueApplicationConfig = $glueApplicationConfig;
        $this->requestFormatterPlugins = $requestFormatterPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    public function formatRequest(HttpRequest $request): RestRequestInterface
    {
        $metadata = $this->requestMetaDataExtractor->extract($request);
        $resource = $this->requestResourceExtractor->extract($request, $metadata);

        $requestBuilder = $this->createRequestResourceBuilder($resource);

        $requestBuilder
            ->addRouteContext($request->attributes->get(RequestConstantsInterface::ATTRIBUTE_CONTEXT, []))
            ->addMetadata($metadata);

        $this->setFields($request, $requestBuilder);
        $this->setParentResources($request, $requestBuilder);

        $requestBuilder = $this->executeRequestFormatterPlugins($requestBuilder, $request);

        return $requestBuilder->build();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    protected function executeRequestFormatterPlugins(RequestBuilderInterface $requestBuilder, HttpRequest $request): RequestBuilderInterface
    {
        foreach ($this->requestFormatterPlugins as $requestFormatterPlugin) {
            $requestBuilder = $requestFormatterPlugin->format($requestBuilder, $request);
        }

        return $requestBuilder;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     *
     * @return void
     */
    protected function setFields(HttpRequest $request, RequestBuilderInterface $requestBuilder): void
    {
        $queryParameters = $request->query->all();

        $requestBuilder->addHttpRequest($request);

        $this->setIncludeFields($requestBuilder, $queryParameters);
        $this->setSparseFields($requestBuilder, $queryParameters);

        $this->setSortFields($requestBuilder, $queryParameters);
        $this->setPaginationFields($requestBuilder, $queryParameters);
        $this->setFilterFields($requestBuilder, $queryParameters);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     *
     * @return void
     */
    protected function setParentResources(HttpRequest $httpRequest, RequestBuilderInterface $requestBuilder): void
    {
        $parentResources = $this->requestResourceExtractor->extractParentResources($requestBuilder->getResource()->getType(), $httpRequest);
        foreach ($parentResources as $parentResource) {
            $requestBuilder->addParentResource($parentResource);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     * @param array $queryParameters
     *
     * @return void
     */
    protected function setSortFields(RequestBuilderInterface $requestBuilder, array $queryParameters): void
    {
        if (!isset($queryParameters[RequestConstantsInterface::QUERY_SORT]) || empty($queryParameters[RequestConstantsInterface::QUERY_SORT])) {
            return;
        }

        $sortFields = explode(',', $queryParameters[RequestConstantsInterface::QUERY_SORT]);
        foreach ($sortFields as $field) {
            $direction = SortInterface::SORT_ASC;
            if ($field[0] === '-') {
                $direction = SortInterface::SORT_DESC;
                $field = trim($field, '-');
            }
            $requestBuilder->addSort($field, $direction);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     * @param array $queryParameters
     *
     * @return void
     */
    protected function setIncludeFields(
        RequestBuilderInterface $requestBuilder,
        array $queryParameters
    ): void {

        if (!isset($queryParameters[RequestConstantsInterface::QUERY_INCLUDE])) {
            $requestBuilder->setExcludeRelationship(
                !$this->glueApplicationConfig->isEagerRelationshipsLoadingEnabled()
            );

            return;
        }

        if (!$queryParameters[RequestConstantsInterface::QUERY_INCLUDE]) {
            $requestBuilder->setExcludeRelationship(true);

            return;
        }

        $includes = explode(',', trim($queryParameters[RequestConstantsInterface::QUERY_INCLUDE]));
        if (count($includes) === 0) {
            $requestBuilder->setExcludeRelationship(true);

            return;
        }

        foreach ($includes as $include) {
            $requestBuilder->addInclude($include, $include);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     * @param array $queryParameters
     *
     * @return void
     */
    protected function setSparseFields(
        RequestBuilderInterface $requestBuilder,
        array $queryParameters
    ): void {

        if (!isset($queryParameters[RequestConstantsInterface::QUERY_FIELDS]) ||
            !is_array($queryParameters[RequestConstantsInterface::QUERY_FIELDS])) {
            return;
        }

        foreach ($queryParameters[RequestConstantsInterface::QUERY_FIELDS] as $resource => $fields) {
            $requestBuilder->addFields($resource, explode(',', $fields));
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     * @param array $queryParameters
     *
     * @return void
     */
    protected function setPaginationFields(RequestBuilderInterface $requestBuilder, array $queryParameters): void
    {
        if (!isset($queryParameters[RequestConstantsInterface::QUERY_PAGE])) {
            return;
        }

        $page = $queryParameters[RequestConstantsInterface::QUERY_PAGE];
        if (isset($page[RequestConstantsInterface::QUERY_OFFSET], $page[RequestConstantsInterface::QUERY_LIMIT])) {
            $requestBuilder->addPage($page[RequestConstantsInterface::QUERY_OFFSET], $page[RequestConstantsInterface::QUERY_LIMIT]);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     * @param array $queryParameters
     *
     * @return void
     */
    protected function setFilterFields(RequestBuilderInterface $requestBuilder, array $queryParameters): void
    {
        if (!isset($queryParameters[RequestConstantsInterface::QUERY_FILTER]) || !is_array($queryParameters[RequestConstantsInterface::QUERY_FILTER])) {
            return;
        }

        foreach ($queryParameters[RequestConstantsInterface::QUERY_FILTER] as $key => $value) {
            [$resource, $field] = explode('.', $key);
            $requestBuilder->addFilter($resource, $field, $value);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    protected function createRequestResourceBuilder(RestResourceInterface $resource): RequestBuilderInterface
    {
        return new RequestBuilder($resource);
    }
}
