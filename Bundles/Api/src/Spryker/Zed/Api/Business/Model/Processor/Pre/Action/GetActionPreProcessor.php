<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Action;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class GetActionPreProcessor implements PreProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $action = $apiRequestTransfer->getResourceAction();
        $idResource = $apiRequestTransfer->getPath(); //TODO should be resource id

        if ($action !== 'get') {
            return;
        }

        $params = [
            $idResource,
            $apiRequestTransfer->getFilter()
        ];

        $apiRequestTransfer->setResourceParams($params);
    }

}
