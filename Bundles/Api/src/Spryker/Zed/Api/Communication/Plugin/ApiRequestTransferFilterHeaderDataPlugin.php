<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin;

use Generated\Shared\Transfer\ApiRequestTransfer;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 */
class ApiRequestTransferFilterHeaderDataPlugin extends ApiRequestTransferFilterAbstractPlugin
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function filter(ApiRequestTransfer $apiRequestTransfer): ApiRequestTransfer
    {
        $apiRequestTransfer->setHeaderData(
            $this->doFilter(
                $apiRequestTransfer->getHeaderData(),
                $this->getConfig()->getSafeHeaderDataKeys()
            )
        );

        return $apiRequestTransfer;
    }
}
