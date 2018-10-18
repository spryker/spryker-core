<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

use Generated\Shared\Transfer\RestPageOffsetsTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ResponsePagination implements ResponsePaginationInterface
{
    /**
     * @var string
     */
    protected $domainName;

    /**
     * @param string $domainName
     */
    public const HARD_LIMIT = 500;

    /**
     * @param string $domainName
     */
    public function __construct(string $domainName)
    {
        $this->domainName = $domainName;
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

        $domain = sprintf($this->domainName . '/%s?page[offset]=', $restRequest->getResource()->getType());

        $limit = '';
        if ($pageOffsetsTransfer->getLimit()) {
            $limit = '&page[limit]=' . $pageOffsetsTransfer->getLimit();
        }

        $offsetLinks = [
            'next' => $domain . $pageOffsetsTransfer->getNextOffset() . $limit,
            'prev' => $domain . $pageOffsetsTransfer->getPrevOffset() . $limit,
            'last' => $domain . $pageOffsetsTransfer->getLastOffset() . $limit,
            'first' => $domain . 0 . $limit,
        ];

        return array_merge(
            $offsetLinks,
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
        $lastOffset = $this->calculateLastOffset($restResponse, $limit);

        return (new RestPageOffsetsTransfer())
            ->setLimit($limit)
            ->setLastOffset($lastOffset)
            ->setNextOffset($nextOffset)
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
        if ($nextOffset > $totalPages) {
            $nextOffset = (int)(($totalPages / $limit) * $limit);
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
    protected function calculateTotalPages(RestResponseInterface $restResponse, $limit): int
    {
        return (int)($restResponse->getTotals() / $limit);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param int $limit
     *
     * @return int
     */
    protected function calculateLastOffset(RestResponseInterface $restResponse, $limit): int
    {
        return $restResponse->getTotals() - $limit;
    }
}
