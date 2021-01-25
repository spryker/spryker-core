<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\RestApiResource;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacadeInterface getFacade()
 */
class ResourceParametersPreProcessor implements PreProcessorInterface
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

        $elements = [];
        if ($path !== '') {
            $elements[] = $path;
        }

        if (strpos($path, '/') !== false) {
            $elements = explode('/', $path);
        }

        $apiRequestTransfer->setResourceParameters($elements);

        return $apiRequestTransfer;
    }
}
