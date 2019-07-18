<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class QueryString extends SymfonyConstraint
{
    public const OPTION_DISCOUNT_FACADE = 'discountFacade';
    public const OPTION_QUERY_STRING_TYPE = 'queryStringType';

    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacade
     */
    protected $discountFacade;

    /**
     * @var string
     */
    protected $queryStringType;

    /**
     * @return \Spryker\Zed\Discount\Business\DiscountFacade
     */
    public function getDiscountFacade()
    {
        return $this->discountFacade;
    }

    /**
     * @return string
     */
    public function getQueryStringType()
    {
        return $this->queryStringType;
    }
}
