<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Business\Manager;

use Generated\Shared\Cart\ChangeInterface;
use Generated\Shared\ProductOptionCartConnector\CartItemInterface;
use SprykerFeature\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionInterface;

class ProductOptionManager implements ProductOptionManagerInterface
{

    /**
     * @var ProductOptionCartConnectorToProductOptionInterface
     */
    private $productOptionFacade;

    /**
     * @param ProductOptionCartConnectorToProductOptionInterface
     */
    public function __construct(ProductOptionCartConnectorToProductOptionInterface $productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param ChangeInterface $change
     */
    public function expandProductOptions(ChangeInterface $change)
    {
        foreach ($change->getItems() as $cartItem) {
            $this->expandProductOptionTransfers($cartItem);

        }
    }

    /**
     * @param CartItemInterface $cartItem
     */
    public function expandProductOptionTransfers(CartItemInterface $cartItem)
    {
        foreach ($cartItem->getProductOptions() as &$productOption) {

            if (null === $productOption->getIdOptionValueUsage() || null ===  $productOption->getFkLocale()) {
                throw new \RuntimeException('Unable to expand product option. Missing required values: idOptionValueUsage, fkLocale');
            }

            $productOption = $this->productOptionFacade->getProductOption(
                $productOption->getIdOptionValueUsage(),
                $productOption->getFkLocale()
            );

        }
    }
}
