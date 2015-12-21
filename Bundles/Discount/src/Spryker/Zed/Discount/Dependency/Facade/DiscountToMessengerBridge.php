<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Dependency\Facade;

class DiscountToMessengerBridge implements DiscountToMessengerInterface
{

    /**
     * @var \Spryker\Zed\Messenger\Business\MessengerFacade
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\Messenger\Business\MessengerFacade $messengerFacade
     */
    public function __construct($messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }


}
