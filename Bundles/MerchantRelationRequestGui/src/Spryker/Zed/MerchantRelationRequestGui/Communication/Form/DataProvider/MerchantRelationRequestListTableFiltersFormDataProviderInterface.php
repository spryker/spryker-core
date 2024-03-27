<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Form\DataProvider;

interface MerchantRelationRequestListTableFiltersFormDataProviderInterface
{
    /**
     * @return array<string, array<string, int>>
     */
    public function getOptions(): array;
}
