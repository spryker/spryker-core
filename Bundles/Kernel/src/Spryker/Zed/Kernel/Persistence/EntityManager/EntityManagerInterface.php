<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\EntityManager;

use Spryker\Shared\Kernel\Transfer\EntityTransferInterface;

interface EntityManagerInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\EntityTransferInterface $entityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface
     */
    public function save(EntityTransferInterface $entityTransfer);
}
