<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueGroupName extends SymfonyConstraint
{
    public const OPTION_PRODUCT_OPTION_QUERY_CONTAINER = 'productOptionQueryContainer';

    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @return \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    public function getProductOptionQueryContainer()
    {
        return $this->productOptionQueryContainer;
    }
}
