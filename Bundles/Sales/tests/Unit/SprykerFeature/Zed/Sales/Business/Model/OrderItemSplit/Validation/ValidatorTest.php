<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation;

use Propel\Runtime\Collection\Collection;
use SprykerFeature\Zed\Sales\Business\Model\Split\Validation\Validator;
use SprykerFeature\Zed\Sales\Business\Model\Split\Validation\Messages;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscount;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemOption;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
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
     */
    public function testValidateIsDiscounted()
    {
        $validator = $this->getValidator();
        $spySalesOrderItem = $this->getSalesOrderItem();

        $discountCollection = new Collection();
        $discountCollection->append(new SpySalesDiscount());
        $spySalesOrderItem->setDiscounts($discountCollection);

        $validateResponse = $validator->isValid($spySalesOrderItem, 1);
        $validationMessages = $validator->getMessages();

        $this->assertFalse($validateResponse);
        $this->assertEquals(Messages::VALIDATE_DISCOUNTED_MESSAGE, $validationMessages[0]);
    }

    /**
     */
    public function testValidateIsOptionDiscounted()
    {
        $validator = $this->getValidator();
        $spySalesOrderItem = $this->getSalesOrderItem();

        $orderItemOptionDiscount = new SpySalesDiscount();
        $discountCollection = new Collection();
        $discountCollection->append($orderItemOptionDiscount);

        $salesOrderItemOption = new SpySalesOrderItemOption();
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
     * @return SpySalesOrderItem
     */
    protected function getSalesOrderItem($quantity = 2)
    {
        $spySalesOrderItem = new SpySalesOrderItem();
        $spySalesOrderItem->setQuantity($quantity);

        return $spySalesOrderItem;
    }

}
