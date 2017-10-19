<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Mapper;

interface TransferMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function toTransfer(array $data);

    /**
     * @param array $productEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer[]
     */
    public function toTransferCollection(array $productEntityCollection);
}
