<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;

interface ProductPackagingUnitTypeDataProviderInterface
{
    /**
     * @param int|null $idProductPackagingUnitType
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getData(?int $idProductPackagingUnitType = null): ProductPackagingUnitTypeTransfer;

    /**
     * @return array
     */
    public function getOptions(): array;
}
