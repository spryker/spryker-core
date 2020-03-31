<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\DataProvider;

use ArrayObject;

interface MoneyCollectionDataProviderInterface
{
    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getInitialData();

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[] $currentFormMoneyValueCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function mergeMissingMoneyValues(ArrayObject $currentFormMoneyValueCollection);
}
