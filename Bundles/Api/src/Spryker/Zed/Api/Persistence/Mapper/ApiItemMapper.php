<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence\Mapper;

use Generated\Shared\Transfer\ApiItemTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ApiItemMapper implements ApiItemMapperInterface
{

    /**
     * @param array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function toItem($data)
    {
        $apiItemTransfer = new ApiItemTransfer();

        $itemData = [];
        if ($data instanceof AbstractTransfer) {
            $itemData[] = $data->modifiedToArray();
        } else {
            $itemData[] = $data;
        }

        $apiItemTransfer->setData($itemData);

        return $apiItemTransfer;
    }

}
