<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

interface ApiFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function dispatch(ApiRequestTransfer $apiRequestTransfer);

    /**
     * @api
     *
     * @param string $resourceName
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiValidationErrorTransfer[]
     */
    public function validate($resourceName, ApiDataTransfer $apiDataTransfer);

    /**
     * Specification:
     * - Applies configured ApiRequestTransferFilterAbstractPluginInterface plugins on ApiRequestTransfer,
     *   Returns filtered ApiRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function filterApiRequestTransfer(ApiRequestTransfer $apiRequestTransfer): ApiRequestTransfer;
}
