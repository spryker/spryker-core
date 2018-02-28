<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Persistence;

interface CompanyRepositoryInterface
{
    /**
     * Specification:
     * - Retrieve stores related to company
     *
     * @param int $idCompany
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getRelatedStoresByCompanyId(int $idCompany);
}
