<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class PaginationByQueryFilterPreProcessor implements PreProcessorInterface
{
    public const LIMIT = 'limit';
    public const PAGE = 'page';

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
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $queryStrings = $apiRequestTransfer->getQueryData();

        $limitPerPage = $this->apiConfig->getLimitPerPage();
        if (!empty($queryStrings[self::LIMIT])) {
            $limitPerPage = $this->validateLimitRange($queryStrings[self::LIMIT]);
        }

        $page = 1;
        if (!empty($queryStrings[self::PAGE])) {
            $page = $this->validatePageInput($queryStrings[self::PAGE]);
        }

        $offset = ($page - 1) * $limitPerPage;

        $apiRequestTransfer->getFilter()->setOffset($offset);
        $apiRequestTransfer->getFilter()->setLimit($limitPerPage);

        return $apiRequestTransfer;
    }

    /**
     * @param int $page
     *
     * @return int
     */
    protected function validatePageInput($page)
    {
        if ($page < 0) {
            $page = 1;
        }

        return (int)$page;
    }

    /**
     * @param int $limit
     *
     * @return int
     */
    protected function validateLimitRange($limit)
    {
        if ($limit < 0) {
            $limit = $this->apiConfig->getLimitPerPage();
        }
        if ($limit > $this->apiConfig->getMaxLimitPerPage()) {
            $limit = $this->apiConfig->getMaxLimitPerPage();
        }

        return (int)$limit;
    }
}
