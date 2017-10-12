<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business\Model;

use Generated\Shared\Transfer\ProductGroupTransfer;

interface ProductGroupTouchInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return bool
     */
    public function touchProductGroupActive(ProductGroupTransfer $productGroupTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    public function touchProductAbstractGroupsActive(ProductGroupTransfer $productGroupTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return bool
     */
    public function touchProductGroupDeleted(ProductGroupTransfer $productGroupTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    public function touchProductAbstractGroupsDeleted(ProductGroupTransfer $productGroupTransfer);
}
