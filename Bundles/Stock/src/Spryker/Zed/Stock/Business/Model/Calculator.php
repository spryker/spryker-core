<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Business\Model;

class Calculator implements CalculatorInterface
{

    /**
     * @var \Spryker\Zed\Stock\Business\Model\Reader
     */
    protected $readerInterface;

    /**
     * @param \Spryker\Zed\Stock\Business\Model\ReaderInterface $readerInterface
     */
    public function __construct(ReaderInterface $readerInterface)
    {
        $this->readerInterface = $readerInterface;
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        $productEntities = $this->readerInterface->getStocksProduct($sku);
        $quantity = 0;

        foreach ($productEntities as $productEntity) {
            $quantity += $productEntity->getQuantity();
        }

        return $quantity;
    }

}
