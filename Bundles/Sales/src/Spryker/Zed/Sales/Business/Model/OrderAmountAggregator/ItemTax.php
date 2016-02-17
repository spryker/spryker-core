<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToTaxInterface;

class ItemTax implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToTaxInterface $taxFacade
     */
    public function __construct(SalesToTaxInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->assertItemTaxRequirements($orderTransfer);
        $this->addTaxAmountToTaxableItems($orderTransfer->getItems());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $taxableItems
     *
     * @return void
     */
    protected function addTaxAmountToTaxableItems(\ArrayObject $taxableItems)
    {
        foreach ($taxableItems as $item) {
            if (empty($item->getTaxRate())) {
                continue;
            }
            $item->requireUnitGrossPrice()->requireSumGrossPrice();

            $item->setUnitTaxAmount(
                $this->taxFacade->getTaxAmountFromGrossPrice(
                    $item->getUnitGrossPrice(),
                    $item->getTaxRate()
                )
            );

            $item->setSumTaxAmount(
                $this->taxFacade->getTaxAmountFromGrossPrice(
                    $item->getSumGrossPrice(),
                    $item->getTaxRate()
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function assertItemTaxRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireItems();
    }

}
