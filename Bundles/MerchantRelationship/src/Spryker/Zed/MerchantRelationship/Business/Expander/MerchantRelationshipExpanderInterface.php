<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Expander;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function expandMerchantRelationshipTransferByName(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer;
}
