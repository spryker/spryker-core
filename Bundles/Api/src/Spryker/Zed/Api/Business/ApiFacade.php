<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\Api\Business\ApiBusinessFactory getFactory()
 */
class ApiFacade extends AbstractFacade implements ApiFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function dispatch(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFactory()
            ->createDispatcher()
            ->dispatch($apiRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array
     */
    public function validate(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFactory()
            ->createValidator()
            ->validate($apiRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $responseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $responseObject
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transformToResponse(ApiRequestTransfer $requestTransfer, ApiResponseTransfer $responseTransfer, Response $responseObject)
    {
        return $this->getFactory()
            ->createTransformer($requestTransfer)
            ->transform($requestTransfer, $responseTransfer, $responseObject);
    }

}
