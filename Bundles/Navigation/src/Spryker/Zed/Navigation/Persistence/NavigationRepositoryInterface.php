<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence;

use Generated\Shared\Transfer\NavigationCriteriaTransfer;
use Generated\Shared\Transfer\NavigationTransfer;

interface NavigationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\NavigationCriteriaTransfer $navigationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer|null
     */
    public function findNavigationByCriteria(NavigationCriteriaTransfer $navigationCriteriaTransfer): ?NavigationTransfer;

    /**
     * @return \Generated\Shared\Transfer\NavigationTransfer[]
     */
    public function getAllNavigations(): array;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function checkNavigationWithKeyExists(string $key): bool;
}
