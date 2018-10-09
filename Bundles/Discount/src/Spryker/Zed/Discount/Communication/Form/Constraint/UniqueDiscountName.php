<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueDiscountName extends SymfonyConstraint
{
    public const OPTION_DISCOUNT_QUERY_CONTAINER = 'discountQueryContainer';

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @return \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    public function getDiscountQueryContainer()
    {
        return $this->discountQueryContainer;
    }
}
