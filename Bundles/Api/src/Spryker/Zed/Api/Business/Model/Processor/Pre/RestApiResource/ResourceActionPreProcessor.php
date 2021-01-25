<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\RestApiResource;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\ApiConfig;
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
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $resourceId = $apiRequestTransfer->getResourceId();
        $requestType = $apiRequestTransfer->getRequestType();

        $resourceAction = null;
        if ($requestType === ApiConfig::HTTP_METHOD_OPTIONS) {
            $resourceAction = ApiConfig::ACTION_OPTIONS;
        } elseif (!$resourceId && $requestType === 'GET') {
            $resourceAction = ApiConfig::ACTION_INDEX;
        } elseif ($resourceId && $requestType === 'GET') {
            $resourceAction = ApiConfig::ACTION_READ;
        } elseif (!$resourceId && $requestType === 'POST') {
            $resourceAction = ApiConfig::ACTION_CREATE;
        } elseif ($resourceId && $requestType === 'PATCH') {
            $resourceAction = ApiConfig::ACTION_UPDATE;
        } elseif ($resourceId && $requestType === 'DELETE') {
            $resourceAction = ApiConfig::ACTION_DELETE;
        }
        if ($resourceAction === null) {
            throw new BadRequestHttpException(sprintf('Request type %s does not fit to provided REST URI.', $requestType), null, ApiConfig::HTTP_CODE_NOT_ALLOWED);
        }

        $apiRequestTransfer->setResourceAction($resourceAction);

        return $apiRequestTransfer;
    }
}
