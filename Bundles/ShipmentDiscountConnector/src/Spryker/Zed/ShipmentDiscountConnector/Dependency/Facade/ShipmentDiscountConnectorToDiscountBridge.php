<?php


namespace Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade;


use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\DiscountFacadeInterface;

class ShipmentDiscountConnectorToDiscountBridge implements ShipmentDiscountConnectorToDiscountInterface
{

    /**
     * @var DiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @param DiscountFacadeInterface $discountFacade
     */
    public function __construct($discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $compareWith
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function queryStringCompare(ClauseTransfer $clauseTransfer, $compareWith)
    {
        return $this->discountFacade->queryStringCompare($clauseTransfer, $compareWith);
    }

}