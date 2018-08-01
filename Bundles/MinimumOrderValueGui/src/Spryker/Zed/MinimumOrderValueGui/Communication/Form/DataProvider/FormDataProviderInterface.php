<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\StoreCurrencyTransfer;

interface FormDataProviderInterface
{
    /**
     * @param array $defaultData
     * @param \Generated\Shared\Transfer\StoreCurrencyTransfer $storeCurrencyTransfer
     *
     * @return array
     */
    public function getData(
        array $defaultData,
        StoreCurrencyTransfer $storeCurrencyTransfer
    ): array;

    /**
     * @return array
     */
    public function getOptions();
}
