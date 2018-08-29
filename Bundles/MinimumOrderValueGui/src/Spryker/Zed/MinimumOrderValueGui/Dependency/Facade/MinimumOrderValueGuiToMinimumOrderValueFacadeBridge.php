<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Dependency\Facade;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class MinimumOrderValueGuiToMinimumOrderValueFacadeBridge implements MinimumOrderValueGuiToMinimumOrderValueFacadeInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacadeInterface
     */
    protected $minimumOrderValueFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacadeInterface $minimumOrderValueFacade
     */
    public function __construct($minimumOrderValueFacade)
    {
        $this->minimumOrderValueFacade = $minimumOrderValueFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function saveMinimumOrderValue(
        MinimumOrderValueTransfer $minimumOrderValueTValueTransfer
    ): MinimumOrderValueTransfer {
        return $this->minimumOrderValueFacade->saveMinimumOrderValue($minimumOrderValueTValueTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function findMinimumOrderValues(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        return $this->minimumOrderValueFacade->findMinimumOrderValues($storeTransfer, $currencyTransfer);
    }

    /**
     * @return int|null
     */
    public function findMinimumOrderValueTaxSetId(): ?int
    {
        return $this->minimumOrderValueFacade->findMinimumOrderValueTaxSetId();
    }

    /**
     * @param int $idTaxSet
     *
     * @return void
     */
    public function saveMinimumOrderValueTaxSet(int $idTaxSet): void
    {
        $this->minimumOrderValueFacade->saveMinimumOrderValueTaxSet($idTaxSet);
    }
}
