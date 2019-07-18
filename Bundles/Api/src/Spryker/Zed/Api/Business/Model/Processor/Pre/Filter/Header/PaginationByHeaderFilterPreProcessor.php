<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Header;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Exception\ApiDispatchingException;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class PaginationByHeaderFilterPreProcessor implements PreProcessorInterface
{
    public const RANGE = 'range';

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
        $headers = $apiRequestTransfer->getHeaderData();
        if (empty($headers[static::RANGE])) {
            return $apiRequestTransfer;
        }

        preg_match('/[A-Za-z]+=([0-9]+)-([0-9]+)/', $headers[static::RANGE][0], $matches);
        if (!$matches) {
            return $apiRequestTransfer;
        }

        $offset = $this->validateOffset($matches[1]);
        $limit = $this->validateLimitRange($matches[2] - $offset + 1);
        $this->validateOffsetAndLimitFitsToPage($limit, $offset);

        $apiRequestTransfer->getFilter()->setOffset($offset);
        $apiRequestTransfer->getFilter()->setLimit($limit);

        return $apiRequestTransfer;
    }

    /**
     * @param int $offset
     *
     * @return int
     */
    protected function validateOffset($offset)
    {
        if ($offset < 0) {
            $offset = 0;
        }

        return (int)$offset;
    }

    /**
     * @param int $limit
     *
     * @return int
     */
    protected function validateLimitRange($limit)
    {
        if ($limit < 0 || $limit > $this->apiConfig->getMaxLimitPerPage()) {
            $limit = $this->apiConfig->getLimitPerPage();
        }

        return (int)$limit;
    }

    /**
     * Offset must be 0 based, thus an invalid offset must throw an exception as it cannot translate into pages.
     *
     * @param int $limit
     * @param int $offset
     *
     * @throws \Spryker\Zed\Api\Business\Exception\ApiDispatchingException
     *
     * @return void
     */
    protected function validateOffsetAndLimitFitsToPage($limit, $offset)
    {
        if ($offset % $limit === 0) {
            return;
        }

        throw new ApiDispatchingException('Invalid offset pagination - must be 0 based to translate into pages');
    }
}
