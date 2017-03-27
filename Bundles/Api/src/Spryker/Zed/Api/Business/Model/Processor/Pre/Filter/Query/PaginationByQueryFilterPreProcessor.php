<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class PaginationByQueryFilterPreProcessor implements PreProcessorInterface
{

    const LIMIT = 'limit';
    const PAGE = 'page';

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $queryStrings = $apiRequestTransfer->getQueryData();
        $apiRequestTransfer->getFilter()->getPagination()->setPage(1);
        $apiRequestTransfer->getFilter()->getPagination()->setLimit(20);

        if (!empty($queryStrings[self::PAGE])) {
            $apiRequestTransfer->getFilter()->getPagination()->setPage(
                $this->validatePageInput($queryStrings[self::PAGE])
            );
        }

        if (!empty($queryStrings[self::LIMIT])) {
            $apiRequestTransfer->getFilter()->getPagination()->setLimit(
                $this->validateLimitRange($queryStrings[self::LIMIT])
            );
        }

        // Implement on project level
    }

    protected function validatePageInput($page)
    {
        if ($page < 0) {
            $page = 1;
        }

        return $page;
    }

    protected function validateLimitRange($limit)
    {
        if ($limit < 0 || $limit > 100) {
            $limit = 20;
        }

        return $limit;
    }

}
