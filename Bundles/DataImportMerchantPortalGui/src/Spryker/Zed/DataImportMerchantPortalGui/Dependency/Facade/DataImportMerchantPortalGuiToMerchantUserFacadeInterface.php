<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantUserTransfer;

interface DataImportMerchantPortalGuiToMerchantUserFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function getCurrentMerchantUser(): MerchantUserTransfer;
}
