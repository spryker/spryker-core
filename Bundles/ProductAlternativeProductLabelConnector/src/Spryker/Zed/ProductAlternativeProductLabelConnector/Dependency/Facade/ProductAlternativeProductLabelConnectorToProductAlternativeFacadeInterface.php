<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade;

interface ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface
{
    /**
     * @return int[]
     */
    public function findProductAbstractIdsWhichConcreteHasAlternative(): array;

    /**
     * @param int $idProduct
     *
     * @return bool
     */
    public function isProductApplicableForLabelAlternative(int $idProduct): bool;
}
