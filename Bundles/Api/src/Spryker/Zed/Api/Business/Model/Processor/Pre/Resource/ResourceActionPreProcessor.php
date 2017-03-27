<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Resource;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ResourceActionPreProcessor implements PreProcessorInterface
{

    /**
     * Extracts the path segment responsible for building the resource action
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return void
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $path = $apiRequestTransfer->getPath();
        $identifier = $path;
        if (strpos($identifier, '/') !== false) {
            $identifier = substr($identifier, 0, strpos($identifier, '/'));
        }

        $requestType = $apiRequestTransfer->getRequestType();

        $resourceAction = null;
        if ($identifier === '' && $requestType === 'GET') {
            $resourceAction = 'find';
        } if ($identifier !== '' && $requestType === 'GET') {
            $resourceAction = 'get';
        } elseif ($identifier === '' && $requestType === 'POST') {
            $resourceAction = 'create';
        } elseif ($identifier !== '' && $requestType === 'PATCH') {
            $resourceAction = 'update';
        } elseif ($identifier !== '' && $requestType === 'DELETE') {
            $resourceAction = 'delete';
        }
        if ($resourceAction === null) {
            throw new BadRequestHttpException(sprintf('Request type %s does not fit to provided URI.', $requestType), null, 405);
        }

        $apiRequestTransfer->setResourceAction($resourceAction);
    }

}
