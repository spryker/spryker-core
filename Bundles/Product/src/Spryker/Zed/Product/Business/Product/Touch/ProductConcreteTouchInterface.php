<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Touch;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteTouchInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcrete($idProductConcrete);

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function touchProductConcreteByTransfer(ProductConcreteTransfer $productConcreteTransfer): void;

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteActive($idProductConcrete);

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteInactive($idProductConcrete);

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteDeleted($idProductConcrete);
}
