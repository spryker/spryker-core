<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre;

use Generated\Shared\Transfer\ApiRequestTransfer;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacade getFacade()
 */
class ResourcePreProcessor implements PreProcessorInterface
{

    /**
     * Resolves the first part of the URL path as resource.
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        // PUT orders/1/events/foobar/item/5
        $path = $apiRequestTransfer->getPath();

        $resource = $path;

        $position = strpos($path, '/');
        if ($position !== false) {
            $resource = substr($path, 0, $position);
            $path = substr($path, strlen($resource) + 1);
        } else {
            $path = '';
        }

        $apiRequestTransfer->setResource($resource);

        $apiRequestTransfer->setPath($path);
    }

}
