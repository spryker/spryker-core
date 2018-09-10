<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication\Stub\Constraint;

use Spryker\Zed\Currency\Persistence\CurrencyQueryContainer;
use SprykerTest\Zed\RestRequestValidator\Communication\Stub\RestRequestValidatorToCurrencyQueryContainerBridge;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class Currency extends SymfonyConstraint
{
    /**
     * @var \SprykerTest\Zed\RestRequestValidator\Communication\Stub\RestRequestValidatorToCurrencyQueryContainerInterface
     */
    protected $currencyQueryContainer;

    /**
     * @param array|null $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->currencyQueryContainer =
            new RestRequestValidatorToCurrencyQueryContainerBridge(new CurrencyQueryContainer());
    }

    /**
     * @return \SprykerTest\Zed\RestRequestValidator\Communication\Stub\RestRequestValidatorToCurrencyQueryContainerInterface
     */
    public function getCurrencyQueryContainer()
    {
        return $this->currencyQueryContainer;
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
