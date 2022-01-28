<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\RestApiResource;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class ResourceIdPreProcessor implements PreProcessorInterface
{
    /**
     * Extracts the path segment responsible for building the resource action
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer): ApiRequestTransfer
    {
        $path = $apiRequestTransfer->getPath() ?? '';
        $identifier = $path;
        $delimiterPosition = strpos($path, '/');
        if ($delimiterPosition !== false) {
            $identifier = substr($path, 0, $delimiterPosition);
            $path = substr($path, $delimiterPosition + 1);
        }

        $resourceId = null;
        $identifier = trim($identifier);
        if ($identifier !== '') {
            $resourceId = (int)$identifier;
        }

        $apiRequestTransfer->setResourceId($resourceId);
        $apiRequestTransfer->setPath($path);

        return $apiRequestTransfer;
    }
}
