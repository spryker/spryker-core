<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Dependency\Facade;

interface ShoppingListStorageToCompanyUserFacadeInterface
{
    /**
     * @param int[] $companyUserIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyUserIds(array $companyUserIds): array;
}
