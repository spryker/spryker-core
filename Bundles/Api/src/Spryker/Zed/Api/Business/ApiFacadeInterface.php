<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Symfony\Component\HttpFoundation\Response;

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
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $responseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $responseObject
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transformToResponse(ApiRequestTransfer $requestTransfer, ApiResponseTransfer $responseTransfer, Response $responseObject);

    /**
     * @api
     *
     * @param string $resourceName
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiValidationErrorTransfer[]
     */
    public function validate($resourceName, ApiDataTransfer $apiDataTransfer);

}
