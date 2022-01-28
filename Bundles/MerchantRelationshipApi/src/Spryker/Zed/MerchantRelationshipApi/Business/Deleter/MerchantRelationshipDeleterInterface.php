<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Deleter;

use Generated\Shared\Transfer\ApiItemTransfer;

interface MerchantRelationshipDeleterInterface
{
    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function delete(int $idMerchantRelationship): ApiItemTransfer;
}
