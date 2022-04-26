<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator\Constraint;

use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ValidCurrencyAssignedToStoreConstraint extends SymfonyConstraint
{
    /**
     * @var string
     */
    protected const MESSAGE = 'Currency {{ currency }} is not assigned to the store {{ store }}';

    /**
     * @var \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeInterface $storeFacade
     * @param array<string, mixed>|null $options
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
