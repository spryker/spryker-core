<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Post\Action;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface;

class AddActionPostProcessor implements PostProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer)
    {
        $action = $apiRequestTransfer->getResourceAction();
        if ($action !== 'add') {
            return $apiResponseTransfer;
        }
        if ($apiResponseTransfer->getCode() !== null) {
            return $apiResponseTransfer;
        }

        $apiResponseTransfer->setCode(201);

        return $apiResponseTransfer;
    }

}
