<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication\Stub\Constraint;

use Spryker\Zed\Currency\Business\CurrencyFacade;
use SprykerTest\Zed\RestRequestValidator\Communication\Stub\RestRequestValidatorToCurrencyFacadeBridge;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class Currency extends SymfonyConstraint
{
    /**
     * @var \SprykerTest\Zed\RestRequestValidator\Communication\Stub\RestRequestValidatorToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param array|null $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->currencyFacade =
            new RestRequestValidatorToCurrencyFacadeBridge(new CurrencyFacade());
    }

    /**
     * @return \SprykerTest\Zed\RestRequestValidator\Communication\Stub\RestRequestValidatorToCurrencyFacadeInterface
     */
    public function getCurrencyFacade()
    {
        return $this->currencyFacade;
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
