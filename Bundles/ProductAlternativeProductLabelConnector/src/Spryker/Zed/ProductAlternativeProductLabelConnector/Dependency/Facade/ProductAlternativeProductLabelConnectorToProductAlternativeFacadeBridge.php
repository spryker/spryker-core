<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade;

class ProductAlternativeProductLabelConnectorToProductAlternativeFacadeBridge implements ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface $productAlternativeFacade
     */
    public function __construct($productAlternativeFacade)
    {
        $this->productAlternativeFacade = $productAlternativeFacade;
    }

    /**
     * @return int[]
     */
    public function findProductAbstractIdsWhichConcreteHasAlternative(): array
    {
        return $this->productAlternativeFacade->findProductAbstractIdsWhichConcreteHasAlternative();
    }

    /**
     * @param int $idProduct
     *
     * @return bool
     */
    public function isProductApplicableForLabelAlternative(int $idProduct): bool
    {
        return $this->productAlternativeFacade->isProductApplicableForLabelAlternative($idProduct);
    }
}
