<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Resource;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacade getFacade()
 */
class ResourceParamsPreProcessor implements PreProcessorInterface
{

    /**
     * Maps all remaining path segments as resource params.
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $path = $apiRequestTransfer->getPath();

        $elements = [$path];
        if (strpos($path, '/') !== false) {
            $elements = explode('/', $path);
        }

        $apiRequestTransfer->setResourceParams($elements);

        return $apiRequestTransfer;
    }

}
