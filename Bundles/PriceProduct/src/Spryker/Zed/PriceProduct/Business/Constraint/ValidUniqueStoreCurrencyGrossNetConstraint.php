<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Constraint;

use Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ValidUniqueStoreCurrencyGrossNetConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'The set of inputs Store and Currency needs to be unique.';

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface
     */
    protected $priceProductRepository;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface $priceProductRepository
     * @param null $options
     */
    public function __construct(PriceProductRepositoryInterface $priceProductRepository, $options = null)
    {
        $this->priceProductRepository = $priceProductRepository;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface
     */
    public function getPriceProductRepository(): PriceProductRepositoryInterface
    {
        return $this->priceProductRepository;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
