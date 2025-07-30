<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantFileExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantFileTransfer;

interface MerchantFilePostSavePluginInterface
{
    /**
     * Specification:
     * - Plugin is executed after a merchant file is saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    public function execute(MerchantFileTransfer $merchantFileTransfer): MerchantFileTransfer;
}
