<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductClassSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function saveProductClassesForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
