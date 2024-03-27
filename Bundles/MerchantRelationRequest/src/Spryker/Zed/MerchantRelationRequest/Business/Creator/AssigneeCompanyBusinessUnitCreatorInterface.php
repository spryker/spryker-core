<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Creator;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;

interface AssigneeCompanyBusinessUnitCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function createAssigneeCompanyBusinessUnits(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer;
}
