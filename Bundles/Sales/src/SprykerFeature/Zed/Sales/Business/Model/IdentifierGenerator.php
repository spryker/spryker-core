<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;

class IdentifierGenerator
{
    /**
     * @var \SprykerEngine\Zed\Kernel\Factory\FactoryInterface
     */
    protected $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }


    /**
     * Returns a unique identifier that can be used to group items
     * example: group items in cart by sku + priceToPay, maybe useful for vouchers
     *
     * @param SprykerFeature\Shared\Sales\Transfer\OrderItem|\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @return string
     */
    public function generateUniqueIdentifierForItem($item)
    {
        $seperator = $this->factory->createSalesSettings()->getUniqueIdentifierSeperator();
        // By default we only return the sku + options if available
        // Overwritte in project to get more complex grouping
        $identifier = $item->getSku();

        /* @var SprykerFeature\Shared\Sales\Transfer\OrderItemOption $option */
        foreach ($item->getOptions() as $option) {
            $identifier .= $seperator . $option->getIdentifier();
        }
        return $identifier;
    }

    /**
     * @param \SprykerFeature\Shared\Sales\Transfer\OrderItem $item
     */
    public function addUniqueIdentifierForItem(\SprykerFeature\Shared\Sales\Transfer\OrderItem $item)
    {
        $identifier = $this->generateUniqueIdentifierForItem($item);
        $item->setUniqueIdentifier($identifier);
    }

    /**
     * @param \SprykerFeature\Shared\Sales\Transfer\OrderItemCollection $itemCollection
     */
    public function addUniqueIdentifierForItemCollection(\SprykerFeature\Shared\Sales\Transfer\OrderItemCollection $itemCollection)
    {
        foreach ($itemCollection as $item) {
            $this->addUniqueIdentifierForItem($item);
        }
    }
}
