<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade;

interface ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface
{
    /**
     * @return array<int>
     */
    public function findProductAbstractIdsWhichConcreteHasAlternative(): array;

    /**
     * @param array<int> $productIds
     *
     * @return bool
     */
    public function doAllConcreteProductsHaveAlternatives(array $productIds): bool;

    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(int $idProductConcrete): bool;
}
