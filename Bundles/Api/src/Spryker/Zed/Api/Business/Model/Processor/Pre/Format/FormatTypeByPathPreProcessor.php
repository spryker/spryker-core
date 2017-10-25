<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Format;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class FormatTypeByPathPreProcessor implements PreProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        // GET orders/1.json
        $path = $apiRequestTransfer->getPath();

        $formatType = null;
        $position = strrpos($path, '.');
        if ($position !== false) {
            $formatType = substr($path, $position + 1);
            $path = substr($path, 0, -strlen($formatType) - 1);
        }
        $apiRequestTransfer->setFormatType($formatType);

        $apiRequestTransfer->setPath($path);

        return $apiRequestTransfer;
    }
}
