<?php
namespace Stock\Helper;

use Generated\Shared\DataBuilder\StockProductBuilder;
use Generated\Shared\DataBuilder\TypeBuilder;
use Testify\Module\BusinessLocator;

class StockData extends \Codeception\Module
{

    public function haveProductInStock($override = [])
    {
        $stockFacade = $this->getStockFacade();
        $stockType = (new TypeBuilder())->build();
        $stockFacade->createStockType($stockType);
        $stockFacade->createStockProduct((new StockProductBuilder($override))->build()->setStockType($stockType->getName()));

    }

    /**
     * @return \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    private function getStockFacade()
    {
        $locator = $this->getLocator();

        return $locator->getLocator()->stock()->facade();
    }

    /**
     * @return BusinessLocator
     */
    private function getLocator()
    {
        return $this->getModule('\\' . BusinessLocator::class);
    }


}