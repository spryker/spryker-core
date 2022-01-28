<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Creator;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ApiDataCreator implements ApiDataCreatorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $transfer
     * @param string|null $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItemTransfer(?AbstractTransfer $transfer = null, ?string $id = null): ApiItemTransfer
    {
        return (new ApiItemTransfer())
            ->setId($id)
            ->setData($transfer ? $transfer->modifiedToArray(true, true) : []);
    }

    /**
     * @param array<\Spryker\Shared\Kernel\Transfer\AbstractTransfer> $transfers
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function createApiCollectionTransfer(array $transfers = []): ApiCollectionTransfer
    {
        $apiCollectionTransfer = new ApiCollectionTransfer();

        foreach ($transfers as $transfer) {
            $apiCollectionTransfer->addData($transfer->modifiedToArray(true, true));
        }

        return $apiCollectionTransfer;
    }
}
