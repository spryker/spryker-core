<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator\Constraint;

use Spryker\Service\PriceProduct\PriceProductServiceInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ValidUniqueStoreCurrencyCollectionConstraint extends SymfonyConstraint
{
    /**
     * @var string
     */
    protected const MESSAGE = 'The set of inputs Store and Currency needs to be unique.';

    /**
     * @var \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     * @param mixed $options
     */
    public function __construct(PriceProductServiceInterface $priceProductService, $options = null)
    {
        $this->priceProductService = $priceProductService;

        parent::__construct($options);
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

    /**
     * @return \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    public function getPriceProductService(): PriceProductServiceInterface
    {
        return $this->priceProductService;
    }
}
