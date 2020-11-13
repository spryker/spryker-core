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
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    protected $facade;

    protected const MESSAGE = 'PriceProductOfferCollectionTransfer::clss is not in consistent state';

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $facade
     * @param array|null $options
     */
    public function __construct(ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $facade, $options = null)
    {
        $this->facade = $facade;

        parent::__construct($options);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    public function getFacade(): ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
    {
        return $this->facade;
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
