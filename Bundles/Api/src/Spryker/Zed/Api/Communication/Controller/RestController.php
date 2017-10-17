<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Controller;

use Generated\Shared\Transfer\ApiRequestTransfer;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacade getFacade()
 */
class RestController extends AbstractApiController
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function indexAction(ApiRequestTransfer $apiRequestTransfer)
    {
        $result = $this->getFacade()->dispatch($apiRequestTransfer);

        return $result;
    }

    /**
     * @return void
     */
    public function deniedAction()
    {
    }
}
