<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\EntityManager;

use Spryker\Shared\Kernel\Transfer\EntityTransferInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface EntityManagerInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface $entityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function save(EntityTransferInterface $entityTransfer);
}
