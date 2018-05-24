<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\Model;

use Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer;

interface ProductAlternativeWriterInterface
{
    /**
     * TODO: Replace transfer that is returned to proper one
     * TODO: Review this specification again, when code will be finished
     *
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): SpyProductAlternativeEntityTransfer;

    /**
     * TODO: Replace transfer that is returned to proper one
     * TODO: Review this specification again, when code will be finished
     *
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): SpyProductAlternativeEntityTransfer;
}
