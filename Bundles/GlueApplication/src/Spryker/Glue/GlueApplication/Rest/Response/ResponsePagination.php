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

        $limit = $restResponse->getLimit() ? $restResponse->getLimit() : $restRequest->getPage()->getLimit();
        $offset = $restRequest->getPage()->getOffset();

        $totalPages = floor($restResponse->getTotals() / $limit);

        $prevOffset = $offset - $limit;
        if ($prevOffset < 0) {
            $prevOffset = 0;
        }

        $nextOffset = $offset + $limit;
        if ($nextOffset > $totalPages) {
            $nextOffset = ($totalPages / $limit) * $limit;
        }

        $restPageOffsetsTransfer = (new RestPageOffsetsTransfer())
            ->setLimit($limit)
            ->setLastOffset($restResponse->getTotals() - $limit)
            ->setNextOffset($nextOffset)
            ->setPrevOffset($prevOffset);

        return $restPageOffsetsTransfer;
    }
}
