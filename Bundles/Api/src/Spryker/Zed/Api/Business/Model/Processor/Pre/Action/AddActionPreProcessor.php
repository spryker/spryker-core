<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Action;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class AddActionPreProcessor implements PreProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $action = $apiRequestTransfer->getResourceAction();
        if ($action !== ApiConfig::ACTION_CREATE) {
            return $apiRequestTransfer;
        }

        $apiDataTransfer = new ApiDataTransfer();

        $postData = (array)$apiRequestTransfer->getRequestData();
        $apiDataTransfer->setData($postData);

        $params = [
            $apiDataTransfer,
        ];

        $apiRequestTransfer->setResourceParameters($params);

        return $apiRequestTransfer;
    }
}
