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
    public const QUERY_PAGE = 'page';
    public const QUERY_PAGES = 'pages';

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
    public function process(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer)
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

        // Add pagination to Meta links
        $metaTransfer = $apiResponseTransfer->getMeta();
        $links = $metaTransfer->getLinks();
        $links['first'] = $pagination->getFirst();
        $links['prev'] = $pagination->getPrev();
        $links['next'] = $pagination->getNext();
        $links['last'] = $pagination->getLast();
        $apiResponseTransfer->getMeta()->setLinks($links);

        $data = $apiResponseTransfer->getMeta()->getData();
        $data[static::QUERY_PAGE] = $pagination->getPage();
        $data[static::QUERY_PAGES] = $pagination->getPageTotal();
        $data['records'] = count($apiResponseTransfer->getData());
        $data['records_per_page'] = $pagination->getItemsPerPage();
        $data['records_total'] = $pagination->getTotal();

        $apiResponseTransfer->getMeta()->setData($data);

        return $apiResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $paginationTransfer
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPaginationTransfer
     */
    protected function generatePaginationUris(ApiPaginationTransfer $paginationTransfer, ApiRequestTransfer $apiRequestTransfer)
    {
        $query = $apiRequestTransfer->getQueryData();

        $paginationTransfer->setFirst($this->generateUri($apiRequestTransfer->getResource(), 1, $query));
        $paginationTransfer->setLast($this->generateUri($apiRequestTransfer->getResource(), $paginationTransfer->getPageTotal(), $query));

        $currentPage = $paginationTransfer->getPage();
        if ($currentPage > 1) {
            $paginationTransfer->setPrev($this->generateUri($apiRequestTransfer->getResource(), $currentPage - 1, $query));
        }
        if ($currentPage < $paginationTransfer->getPageTotal()) {
            $paginationTransfer->setNext($this->generateUri($apiRequestTransfer->getResource(), $currentPage + 1, $query));
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
    protected function generateUri($resource, $page, $query)
    {
        $query[static::QUERY_PAGE] = $page;
        $url = Url::generate($this->apiConfig->getBaseUri() . $resource, $query);

        return $url->build();
    }
}
