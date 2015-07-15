<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Business\Model;

class Calculator implements CalculatorInterface
{

    /**
     * @var Reader
     */
    protected $readerInterface;

    /**
     * @param ReaderInterface $readerInterface
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
