<?php


namespace Spryker\Zed\OfferGui\Dependency\Facade;


use Spryker\Zed\Sales\Business\SalesFacadeInterface;

class OfferGuiToSalesFacadeBridge implements OfferGuiToSalesFacadeInterface
{
    /**
     * @var SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param SalesFacadeInterface $salesFacade
     */
    public function __construct($salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->salesFacade->getOrderByIdSalesOrder($idSalesOrder);
    }
}