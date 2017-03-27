<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class SortByQueryFilterPreProcessor implements PreProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $queryStrings = $apiRequestTransfer->getQueryData();
        if (empty($queryStrings['sort'])) {
            return;
        }

        $sortString = $queryStrings['sort'];
        $sortCollection = explode(',', $sortString);

        $sort = [];
        foreach ($sortCollection as $sortItemString) {
            $order = Criteria::ASC;
            $column = $sortItemString;

            if ($sortItemString[0] === '-') {
                $column = substr($sortItemString, 1);
                $order = Criteria::DESC;
            }

            $sort[$column] = $order;
        }

        $apiRequestTransfer->getFilter()->setSort($sort);
    }

}
