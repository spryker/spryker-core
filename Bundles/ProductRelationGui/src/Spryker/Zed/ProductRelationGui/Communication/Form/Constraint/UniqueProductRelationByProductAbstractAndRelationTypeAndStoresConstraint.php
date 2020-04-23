<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Form\Constraint;

use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductRelationFacadeInterface;
use Symfony\Component\Validator\Constraint;

class UniqueProductRelationByProductAbstractAndRelationTypeAndStoresConstraint extends Constraint
{
    public const OPTION_PRODUCT_RELATION_FACADE = 'productRelationFacade';

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @return \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductRelationFacadeInterface
     */
    public function getProductRelationFacade(): ProductRelationGuiToProductRelationFacadeInterface
    {
        return $this->productRelationFacade;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
