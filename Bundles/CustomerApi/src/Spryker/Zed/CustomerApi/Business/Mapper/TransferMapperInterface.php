<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Mapper;

interface TransferMapperInterface
{
    /**
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer
     */
    public function toTransfer(array $data);

    /**
     * @param array<string, mixed> $data
     *
     * @return array<\Generated\Shared\Transfer\CustomerApiTransfer>
     */
    public function toTransferCollection(array $data);
}
