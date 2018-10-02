<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Post\Filter\Header;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface;

class PaginationByHeaderFilterPostProcessor implements PostProcessorInterface
{
    public const HEADER_ACCEPT_RANGES = 'Accept-Ranges';
    public const HEADER_CONTENT_RANGE = 'Content-Range';

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer)
    {
        $headers = $apiRequestTransfer->getHeaderData();
        if (empty($headers['range'])) {
            return $apiResponseTransfer;
        }

        $pagination = $apiResponseTransfer->getPagination();
        if (!$pagination) {
            return $apiResponseTransfer;
        }

        if ($pagination->getPage() && $pagination->getItemsPerPage() && $pagination->getTotal()) {
            $headers = $apiResponseTransfer->getHeaders();
            $headers[static::HEADER_ACCEPT_RANGES] = $apiRequestTransfer->getResource();

            $from = $pagination->getPage() * $pagination->getItemsPerPage() - 1;
            $to = $from + $pagination->getItemsPerPage();
            $total = $pagination->getTotal();
            $headers[static::HEADER_CONTENT_RANGE] = $apiRequestTransfer->getResource() . ' ' . $from . '-' . $to . '/' . $total;
            $apiResponseTransfer->setHeaders($headers);
        }

        return $apiResponseTransfer;
    }
}
