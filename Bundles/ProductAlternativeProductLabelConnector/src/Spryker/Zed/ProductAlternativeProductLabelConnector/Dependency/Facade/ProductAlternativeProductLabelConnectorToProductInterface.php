<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade;

interface ProductAlternativeProductLabelConnectorToProductInterface
{
    /**
     * @param int $idProduct
     *
     * @return int|null
     */
    public function findProductAbstractIdByConcreteId(int $idProduct): ?int;

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function findProductConcreteIdsByAbstractProductId(int $idProductAbstract): array;
}
