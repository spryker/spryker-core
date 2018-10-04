<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueRelationTypeForProductAbstract extends SymfonyConstraint
{
    public const OPTION_PRODUCT_RELATION_QUERY_CONTAINER = 'productRelationQueryContainer';

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @return \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    public function getProductRelationQueryContainer()
    {
        return $this->productRelationQueryContainer;
    }

    /**
     * @return string
     */
    public function getTargets()
    {
         return static::CLASS_CONSTRAINT;
    }
}
