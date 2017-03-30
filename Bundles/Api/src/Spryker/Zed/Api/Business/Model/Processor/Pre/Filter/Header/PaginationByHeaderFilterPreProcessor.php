<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Header;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class PaginationByHeaderFilterPreProcessor implements PreProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $headers = $apiRequestTransfer->getHeaderData();
        if (empty($headers['range'])) {
            return $apiRequestTransfer;
        }

        preg_match('/[a-z]+=(\d)-(\d)/', $headers['range'][0], $matches);
        if (!$matches) {
            return $apiRequestTransfer;
        }

        $apiRequestTransfer->getFilter()->getPagination()->setOffset($matches[1]);
        $apiRequestTransfer->getFilter()->getPagination()->setLimit($matches[2] - $matches[1]);

        return $apiRequestTransfer;
    }

}
