<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

use Generated\Shared\Transfer\RestPageOffsetsTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;

class ResponsePagination implements ResponsePaginationInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $config;

    /**
     * @param string $domainName
     */
    public const HARD_LIMIT = 500;

    /**
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $config
     */
    public function __construct(GlueApplicationConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    public function buildPaginationLinks(
        RestResponseInterface $restResponse,
        RestRequestInterface $restRequest
    ): array {
        $pageOffsetsTransfer = $this->calculatePaginationOffset($restRequest, $restResponse);

        if (!$pageOffsetsTransfer) {
            return [];
        }

        $resourceLink = $this->buildResourceLink($restRequest);
        $limitParameter = $this->buildLimitParameter($pageOffsetsTransfer);

        $paginationLinks = [
            RestLinkInterface::LINK_LAST => $this->getPaginationLink($resourceLink, $pageOffsetsTransfer->getLastOffset(), $limitParameter),
            RestLinkInterface::LINK_FIRST => $this->getPaginationLink($resourceLink, $pageOffsetsTransfer->getFirstOffset(), $limitParameter),
        ];

        if ($restRequest->getPage()->getOffset() > 0) {
            $paginationLinks[RestLinkInterface::LINK_PREV]
                = $this->getPaginationLink($resourceLink, $pageOffsetsTransfer->getPrevOffset(), $limitParameter);
        }
        if ($pageOffsetsTransfer->getNextOffset() < $restResponse->getTotals()) {
            $paginationLinks[RestLinkInterface::LINK_NEXT]
                = $this->getPaginationLink($resourceLink, $pageOffsetsTransfer->getNextOffset(), $limitParameter);
        }

        return array_merge(
            $paginationLinks,
            $restResponse->getLinks()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Generated\Shared\Transfer\RestPageOffsetsTransfer|null
     */
    protected function calculatePaginationOffset(
        RestRequestInterface $restRequest,
        RestResponseInterface $restResponse
    ): ?RestPageOffsetsTransfer {
        if (!$restRequest->getPage() || !$restResponse->getTotals()) {
            return null;
        }

        $limit = $this->getLimit($restRequest, $restResponse);
        $offset = $restRequest->getPage()->getOffset();

        $totalPages = $this->calculateTotalPages($restResponse, $limit);
        $prevOffset = $this->calculatePreviousOffset($offset, $limit);
        $nextOffset = $this->calculateNextOffset($offset, $limit, $totalPages);
        $lastOffset = $this->calculateLastOffset($limit, $totalPages);

        return (new RestPageOffsetsTransfer())
            ->setLimit($limit)
            ->setLastOffset($lastOffset)
            ->setNextOffset($nextOffset)
            ->setFirstOffset(0)
            ->setPrevOffset($prevOffset);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return int
     */
    protected function getLimit(RestRequestInterface $restRequest, RestResponseInterface $restResponse): int
    {
        $inputPageLimit = static::HARD_LIMIT;
        if ($restRequest->getPage()) {
            $inputPageLimit = $restRequest->getPage()->getLimit();
        }

        return $restResponse->getLimit() ? $restResponse->getLimit() : $inputPageLimit;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param int $totalPages
     *
     * @return int
     */
    protected function calculateNextOffset(int $offset, int $limit, int $totalPages): int
    {
        $nextOffset = $offset + $limit;
        if ($nextOffset > $totalPages * $limit) {
            $nextOffset = (int)($totalPages * $limit);
        }

        return $nextOffset;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return int
     */
    protected function calculatePreviousOffset(int $offset, int $limit): int
    {
        $prevOffset = $offset - $limit;
        if ($prevOffset < 0) {
            $prevOffset = 0;
        }

        return $prevOffset;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param int $limit
     *
     * @return int
     */
    protected function calculateTotalPages(RestResponseInterface $restResponse, int $limit): int
    {
        return ceil($restResponse->getTotals() / $limit);
    }

    /**
     * @param int $limit
     * @param int $totalPages
     *
     * @return int
     */
    protected function calculateLastOffset(int $limit, int $totalPages): int
    {
        return $limit * ($totalPages - 1);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string
     */
    protected function buildResourceLink(RestRequestInterface $restRequest): string
    {
        $queryString = $restRequest->getQueryString([RequestConstantsInterface::QUERY_PAGE]);

        $resourceLinks = [];
        $parentResources = $restRequest->getParentResources();
        foreach ($parentResources as $parentResource) {
            $resourceLinks[] = sprintf('%s/%s', $parentResource->getType(), $parentResource->getId());
        }

        $resourceLinks[] = $restRequest->getResource()->getType();

        return sprintf(
            '%s/%s?%s',
            $this->config->getGlueDomainName(),
            implode('/', $resourceLinks),
            ($queryString ? $queryString . '&' : '')
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestPageOffsetsTransfer $pageOffsetsTransfer
     *
     * @return string
     */
    protected function buildLimitParameter(RestPageOffsetsTransfer $pageOffsetsTransfer): string
    {
        $limit = '';
        if ($pageOffsetsTransfer->getLimit()) {
            $limit = sprintf(
                '&%s[%s]=%s',
                RequestConstantsInterface::QUERY_PAGE,
                RequestConstantsInterface::QUERY_LIMIT,
                $pageOffsetsTransfer->getLimit()
            );
        }

        return $limit;
    }

    /**
     * @param int $offset
     *
     * @return string
     */
    protected function buildOffsetParameter(int $offset): string
    {
        return sprintf(
            '%s[%s]=%s',
            RequestConstantsInterface::QUERY_PAGE,
            RequestConstantsInterface::QUERY_OFFSET,
            (string)$offset
        );
    }

    /**
     * @param string $domain
     * @param int $offset
     * @param string $limit
     *
     * @return string
     */
    protected function getPaginationLink(string $domain, int $offset, string $limit): string
    {
        return sprintf('%s%s%s', $domain, $this->buildOffsetParameter($offset), $limit);
    }
}
