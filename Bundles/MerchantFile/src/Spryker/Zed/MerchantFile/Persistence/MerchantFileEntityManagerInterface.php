<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Persistence;

use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\EntityManagerInterface;

interface MerchantFileEntityManagerInterface extends EntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    public function saveMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileTransfer;
}
