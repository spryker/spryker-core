<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Business\Manager;

use Generated\Shared\Cart\ChangeInterface;
use Generated\Shared\ProductOptionCartConnector\ItemInterface;
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
     *
     * @return ChangeInterface
     */
    public function expandProductOptions(ChangeInterface $change)
    {
        foreach ($change->getItems() as $cartItem) {
            $this->expandProductOptionTransfers($cartItem);
        }

        return $change;
    }

    /**
     * @param ItemInterface $cartItem
     */
    public function expandProductOptionTransfers(ItemInterface $cartItem)
    {
        foreach ($cartItem->getProductOptions() as &$productOptionTransfer) {

            if (null === $productOptionTransfer->getIdOptionValueUsage() || null ===  $productOptionTransfer->getLocaleCode()) {
                throw new \RuntimeException('Unable to expand product option. Missing required values: idOptionValueUsage, localeCode');
            }

            $productOptionTransfer = $this->productOptionFacade->getProductOption(
                $productOptionTransfer->getIdOptionValueUsage(),
                $productOptionTransfer->getLocaleCode()
            );
            $productOptionTransfer->setQuantity($cartItem->getQuantity());
        }
    }
}
