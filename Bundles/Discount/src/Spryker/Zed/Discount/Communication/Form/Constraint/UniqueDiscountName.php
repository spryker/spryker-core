<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Constraint;

use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueDiscountName extends SymfonyConstraint
{
    const OPTION_DISCOUNT_QUERY_CONTAINER = 'discountQueryContainer';

    /**
     * @var DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @return DiscountQueryContainerInterface
     */
    public function getDiscountQueryContainer()
    {
        return $this->discountQueryContainer;
    }
}
