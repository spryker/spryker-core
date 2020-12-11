<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Constraint;

use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ValidProductOfferPriceIdsOwnByMerchantConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'The merchant doesn\'t own the product offer.';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @phpstan-param array<mixed> $options
     *
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade
     * @param array|null $options
     */
    public function __construct(ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade, $options = null)
    {
        $this->priceProductOfferFacade = $priceProductOfferFacade;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    public function getFacade(): ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
    {
        return $this->priceProductOfferFacade;
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
