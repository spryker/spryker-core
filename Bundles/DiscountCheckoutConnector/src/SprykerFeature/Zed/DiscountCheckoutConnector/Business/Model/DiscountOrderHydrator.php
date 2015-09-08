<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\DiscountCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\DiscountCheckoutConnector\OrderInterface;
use SprykerFeature\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountInterface;

class DiscountOrderHydrator implements DiscountOrderHydratorInterface
{

    /**
     * @var DiscountCheckoutConnectorToDiscountInterface
     */
    private $discountFacade;

    /**
     * @param DiscountCheckoutConnectorToDiscountInterface $discountFacade
     */
    public function __construct(DiscountCheckoutConnectorToDiscountInterface $discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrder(OrderInterface $orderTransfer, CheckoutRequestInterface $request)
    {

    }

}
