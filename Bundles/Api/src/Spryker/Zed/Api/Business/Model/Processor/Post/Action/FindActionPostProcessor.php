<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Post\Action;

use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface;

class FindActionPostProcessor implements PostProcessorInterface
{
    /**
     * @var string
     */
    public const QUERY_PAGE = 'page';

    /**
     * @var string
     */
    public const QUERY_PAGES = 'pages';

    /**
     * @var int
     */
    protected const DEFAULT_PAGE_TOTAL = 1;

    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $apiConfig;

    /**
     * @param \Spryker\Zed\Api\ApiConfig $apiConfig
     */
    public function __construct(ApiConfig $apiConfig)
    {
        $this->apiConfig = $apiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer): ApiResponseTransfer
    {
        $action = $apiRequestTransfer->getResourceAction();
        if ($action !== ApiConfig::ACTION_INDEX) {
            return $apiResponseTransfer;
        }
        if ($apiResponseTransfer->getCode() !== null) {
            return $apiResponseTransfer;
        }
        $pagination = $apiResponseTransfer->getPagination();
        if (!$pagination) {
            return $apiResponseTransfer;
        }
        $pagination = $this->generatePaginationUris($pagination, $apiRequestTransfer);

        // Return "Partial Content" as the result doesn't fit on a single page.
        if ($pagination->getPageTotal() > 1) {
            $apiResponseTransfer->setCode(ApiConfig::HTTP_CODE_PARTIAL_CONTENT);
        }

        $metaTransfer = $apiResponseTransfer->getMeta();
        if (!$metaTransfer) {
            return $apiResponseTransfer;
        }

        $links = $metaTransfer->getLinks();
        $links['first'] = $pagination->getFirst();
        $links['prev'] = $pagination->getPrev();
        $links['next'] = $pagination->getNext();
        $links['last'] = $pagination->getLast();
        $metaTransfer->setLinks($links);

        $data = $metaTransfer->getData();
        $data[static::QUERY_PAGE] = $pagination->getPage();
        $data[static::QUERY_PAGES] = $pagination->getPageTotal();
        $data['records'] = count($apiResponseTransfer->getData());
        $data['records_per_page'] = $pagination->getItemsPerPage();
        $data['records_total'] = $pagination->getTotal();
        $metaTransfer->setData($data);

        return $apiResponseTransfer->setMeta($metaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $paginationTransfer
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPaginationTransfer
     */
    protected function generatePaginationUris(
        ApiPaginationTransfer $paginationTransfer,
        ApiRequestTransfer $apiRequestTransfer
    ): ApiPaginationTransfer {
        $query = $apiRequestTransfer->getQueryData();
        $pageTotal = $paginationTransfer->getPageTotal() ?? static::DEFAULT_PAGE_TOTAL;
        $resource = $apiRequestTransfer->getResourceOrFail();

        $paginationTransfer->setFirst($this->generateUri($resource, 1, $query));
        $paginationTransfer->setLast($this->generateUri($resource, $pageTotal, $query));

        $currentPage = $paginationTransfer->getPageOrFail();
        if ($currentPage > 1) {
            $paginationTransfer->setPrev($this->generateUri($resource, $currentPage - 1, $query));
        }
        if ($currentPage < $pageTotal) {
            $paginationTransfer->setNext($this->generateUri($resource, $currentPage + 1, $query));
        }

        return $paginationTransfer;
    }

    /**
     * @param string $resource
     * @param int $page
     * @param array $query
     *
     * @return string
     */
    protected function generateUri($resource, $page, $query): string
    {
        $query[static::QUERY_PAGE] = $page;
        $url = Url::generate($this->apiConfig->getBaseUri() . $resource, $query);

        return $url->build();
    }
}
