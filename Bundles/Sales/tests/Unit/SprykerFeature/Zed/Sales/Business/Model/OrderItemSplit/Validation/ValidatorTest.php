<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation;

use Propel\Runtime\Collection\Collection;
use SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation\Validator;
use SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation\Messages;
use SprykerFeature\Zed\Sales\Persistence;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testInvalidQuantity()
    {
        $validator = $this->getValidator();
        $spySalesOrderItem = $this->getSalesOrderItem(1);

        $validateResponse = $validator->isValid($spySalesOrderItem, 666);
        $validationMessages = $validator->getMessages();

        $this->assertFalse($validateResponse);
        $this->assertEquals(Messages::VALIDATE_QUANTITY_MESSAGE, $validationMessages[0]);
    }

    /**
     * @return void
     */
    public function testValidateIsProductBundled()
    {
        $validator = $this->getValidator();
        $spySalesOrderItem = $this->getSalesOrderItem();
        $spySalesOrderItem->setFkSalesOrderItemBundle(1);

        $validateResponse = $validator->isValid($spySalesOrderItem, 1);
        $validationMessages = $validator->getMessages();

        $this->assertFalse($validateResponse);
        $this->assertEquals(Messages::VALIDATE_BUNDLE_MESSAGE, $validationMessages[0]);
    }

    /**
     * @return void
     */
    public function testValidateIsDiscounted()
    {
        $validator = $this->getValidator();
        $spySalesOrderItem = $this->getSalesOrderItem();

        $discountCollection = new Collection();
        $discountCollection->append(new Persistence\Propel\SpySalesDiscount());
        $spySalesOrderItem->setDiscounts($discountCollection);

        $validateResponse = $validator->isValid($spySalesOrderItem, 1);
        $validationMessages = $validator->getMessages();

        $this->assertFalse($validateResponse);
        $this->assertEquals(Messages::VALIDATE_DISCOUNTED_MESSAGE, $validationMessages[0]);
    }

    /**
     * @return void
     */
    public function testValidateIsOptionDiscounted()
    {
        $validator = $this->getValidator();
        $spySalesOrderItem = $this->getSalesOrderItem();

        $orderItemOptionDiscount = new Persistence\Propel\SpySalesDiscount();
        $discountCollection = new Collection();
        $discountCollection->append($orderItemOptionDiscount);

        $salesOrderItemOption = new Persistence\Propel\SpySalesOrderItemOption();
        $salesOrderItemOption->setDiscounts($discountCollection);

        $optionCollection = new Collection();
        $optionCollection->append($salesOrderItemOption);
        $spySalesOrderItem->setOptions($optionCollection);

        $validateResponse = $validator->isValid($spySalesOrderItem, 1);
        $validationMessages = $validator->getMessages();

        $this->assertFalse($validateResponse);
        $this->assertEquals(Messages::VALIDATE_DISCOUNTED_OPTION_MESSAGE, $validationMessages[0]);
    }

    /**
     * @return void
     */
    public function testValidOrderItem()
    {
        $validator = $this->getValidator();
        $spySalesOrderItem = $this->getSalesOrderItem();

        $validateResponse = $validator->isValid($spySalesOrderItem, 1);

        $this->assertTrue($validateResponse);
    }

    /**
     * @return Validator
     */
    protected function getValidator()
    {
        return new Validator();
    }

    /**
     * @param int $quantity
     *
     * @return Persistence\Propel\SpySalesOrderItem
     */
    protected function getSalesOrderItem($quantity = 2)
    {
        $spySalesOrderItem = new Persistence\Propel\SpySalesOrderItem();
        $spySalesOrderItem->setQuantity($quantity);

        return $spySalesOrderItem;
    }
}
