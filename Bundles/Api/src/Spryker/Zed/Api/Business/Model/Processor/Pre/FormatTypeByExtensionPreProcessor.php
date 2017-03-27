<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre;

use Generated\Shared\Transfer\ApiRequestTransfer;

class FormatTypeByExtensionPreProcessor implements PreProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        // PUT orders/1/events/foobar/item/5.json
        $path = $apiRequestTransfer->getPath();

        $formatType = null;
        $position = strrpos($path, '.');
        if ($position !== false) {
            $formatType = substr($path, $position + 1);
            $path = substr($path, 0, -strlen($formatType) - 1);
        }
        $apiRequestTransfer->setFormatType($formatType);

        $apiRequestTransfer->setPath($path);
    }

}
