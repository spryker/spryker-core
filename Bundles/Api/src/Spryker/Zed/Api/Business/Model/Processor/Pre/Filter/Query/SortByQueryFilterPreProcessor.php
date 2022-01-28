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
    /**
     * @var string
     */
    public const SORT = 'sort';

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer): ApiRequestTransfer
    {
        $queryStrings = $apiRequestTransfer->getQueryData();
        if (empty($queryStrings[static::SORT])) {
            return $apiRequestTransfer;
        }

        $sortString = $queryStrings[static::SORT];
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

        $apiRequestTransfer->getFilterOrFail()->setSort($sort);

        return $apiRequestTransfer;
    }
}
