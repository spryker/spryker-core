<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class SortByQueryFilterPreProcessor implements PreProcessorInterface
{
    public const SORT = 'sort';

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $queryStrings = $apiRequestTransfer->getQueryData();
        if (empty($queryStrings[self::SORT])) {
            return $apiRequestTransfer;
        }

        $sortString = $queryStrings[self::SORT];
        $sortCollection = explode(',', $sortString);

        $sort = [];
        foreach ($sortCollection as $sortItemString) {
            $order = '';
            $column = $sortItemString;

            if ($sortItemString[0] === '-') {
                $column = substr($sortItemString, 1);
                $order = '-';
            }

            $sort[$column] = $order;
        }

        $apiRequestTransfer->getFilter()->setSort($sort);

        return $apiRequestTransfer;
    }
}
