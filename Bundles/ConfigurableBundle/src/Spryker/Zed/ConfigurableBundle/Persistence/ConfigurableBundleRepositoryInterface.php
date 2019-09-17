<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

interface ConfigurableBundleRepositoryInterface
{
    /**
     * @param int $idProductList
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    public function findConfigurableBundleTemplateSlotsByIdProductList(int $idProductList): array;
}
