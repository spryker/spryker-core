<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Action;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacade getFacade()
 */
class UpdateActionPreProcessor implements PreProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $method = $apiRequestTransfer->getResourceAction();
        if ($method !== 'update') {
            return $apiRequestTransfer;
        }

        $postData = (array)$apiRequestTransfer->getRequestData();

        $idResource = $apiRequestTransfer->getPath();
        $apiDataTransfer = new ApiDataTransfer();
        $apiDataTransfer->setData($postData);

        $apiRequestTransfer->setData($apiDataTransfer);

        $params = [
            $idResource,
            $apiDataTransfer,
        ];

        $apiRequestTransfer->setResourceParameters($params);

        return $apiRequestTransfer;
    }

}
