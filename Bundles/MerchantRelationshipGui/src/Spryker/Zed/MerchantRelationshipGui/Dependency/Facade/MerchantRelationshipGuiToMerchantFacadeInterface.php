<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantCollectionTransfer;

interface MerchantRelationshipGuiToMerchantFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchants(): MerchantCollectionTransfer;
}
