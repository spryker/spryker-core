<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Creator;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;

interface MerchantRelationshipCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return void
     */
    public function createMerchantRelationships(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): void;
}
