<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;

interface ProductOptionGroupSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return int
     */
    public function saveProductOptionGroup(ProductOptionGroupTransfer $productOptionGroupTransfer);

    /**
     * @param int $idProductOptionGroup
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException
     *
     * @return bool
     */
    public function toggleOptionActive($idProductOptionGroup, $isActive);
}
