<?php

namespace Stock\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\StockProductBuilder;
use Generated\Shared\DataBuilder\TypeBuilder;
use Testify\Helper\BusinessHelper;

class StockData extends Module
{

    /**
     * @param array $override
     *
     * @return void
     */
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
        return $this->getModule('\\' . BusinessHelper::class)->getLocator()->stock()->facade();
    }

}
