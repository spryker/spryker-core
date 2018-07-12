<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Persistence;

use Generated\Shared\Transfer\OrderSourceTransfer;

interface ManualOrderEntryRepositoryInterface
{
    /**
     * @api
     *
     * @param int $idOrderSource
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer
     */
    public function getOrderSourceById($idOrderSource): OrderSourceTransfer;

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer[]
     */
    public function getAllOrderSources(): array;
}
