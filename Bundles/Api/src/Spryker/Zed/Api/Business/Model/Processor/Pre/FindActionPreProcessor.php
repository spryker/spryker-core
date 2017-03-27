<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre;

use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacade getFacade()
 */
class FindActionPreProcessor implements PreProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $method = $apiRequestTransfer->getResourceAction();
        if ($method !== 'find') {
            return;
        }

        $filter = new ApiFilterTransfer();

        $params = [$filter];
        $apiRequestTransfer->setResourceParams($params);
    }

}
