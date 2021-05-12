<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator\Constraint;

use Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ValidUniqueStoreCurrencyGrossNetConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'The set of inputs Store and Currency needs to be unique.';

    /**
     * @var \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface
     */
    protected $priceProductOfferRepository;

    /**
     * @phpstan-param array<mixed> $options
     *
     * @param \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface $priceProductOfferRepository
     * @param array|null $options
     */
    public function __construct(PriceProductOfferRepositoryInterface $priceProductOfferRepository, $options = null)
    {
        $this->priceProductOfferRepository = $priceProductOfferRepository;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface
     */
    public function getPriceProductOfferRepository(): PriceProductOfferRepositoryInterface
    {
        return $this->priceProductOfferRepository;
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
