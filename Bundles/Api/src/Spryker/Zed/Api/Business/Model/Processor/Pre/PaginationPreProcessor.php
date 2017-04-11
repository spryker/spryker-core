<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre;

use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

class PaginationPreProcessor implements PreProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $apiPaginationTransfer = new ApiPaginationTransfer();
        $apiRequestTransfer->getFilter()->setPagination($apiPaginationTransfer);

        return $apiRequestTransfer;
    }

}
