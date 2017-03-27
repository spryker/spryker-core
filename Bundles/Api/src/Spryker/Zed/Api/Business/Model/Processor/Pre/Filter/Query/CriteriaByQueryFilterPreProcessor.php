<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class CriteriaByQueryFilterPreProcessor implements PreProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $queryStrings = $apiRequestTransfer->getQueryData();
        if (empty($queryStrings['criteria'])) {
            return;
        }

        $criteria = $queryStrings['criteria'];

        //TODO remove
        $criteria = '{"condition":"OR","rules":[{"id":"first_name","field":"first_name","type":"string","input":"text","operator":"equal","value":"John"},{"id":"last_name","field":"last_name","type":"string","input":"text","operator":"equal","value":"Doe"}]}';

        $apiRequestTransfer->getFilter()->setCriteria($criteria);
    }

}
