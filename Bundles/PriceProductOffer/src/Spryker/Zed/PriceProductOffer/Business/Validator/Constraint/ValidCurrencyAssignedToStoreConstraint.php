<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator\Constraint;

use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ValidCurrencyAssignedToStoreConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'Currency "{{ currency }}" is not assigned to the store "{{ store }}"';

    /**
     * @var \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @phpstan-param array<string, mixed> $options
     *
     * @param \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeInterface $storeFacade
     * @param array|null $options
     */
    public function __construct(PriceProductOfferToStoreFacadeInterface $storeFacade, $options = null)
    {
        $this->storeFacade = $storeFacade;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeInterface
     */
    public function getStoreFacade(): PriceProductOfferToStoreFacadeInterface
    {
        return $this->storeFacade;
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
