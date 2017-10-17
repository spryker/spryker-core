<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Mapper;

use Generated\Shared\Transfer\CustomerApiTransfer;

class TransferMapper implements TransferMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer
     */
    public function toTransfer(array $data)
    {
        $customerApiTransfer = new CustomerApiTransfer();
        $customerApiTransfer->fromArray($data, true);

        return $customerApiTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer[]
     */
    public function toTransferCollection(array $data)
    {
        $transferList = [];
        foreach ($data as $itemData) {
            $transferList[] = $this->toTransfer($itemData);
        }

        return $transferList;
    }
}
