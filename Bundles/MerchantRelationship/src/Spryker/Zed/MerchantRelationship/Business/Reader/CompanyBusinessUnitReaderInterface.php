<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Reader;

interface CompanyBusinessUnitReaderInterface
{
    /**
     * @param list<int> $companyBusinessUnitIds
     *
     * @return array<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer>
     */
    public function getCompanyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit(array $companyBusinessUnitIds): array;
}
