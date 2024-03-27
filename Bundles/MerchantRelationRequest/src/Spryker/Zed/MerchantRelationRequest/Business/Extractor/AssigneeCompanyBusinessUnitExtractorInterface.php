<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Extractor;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;

interface AssigneeCompanyBusinessUnitExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return list<int>
     */
    public function extractCompanyBusinessUnitIds(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): array;
}
