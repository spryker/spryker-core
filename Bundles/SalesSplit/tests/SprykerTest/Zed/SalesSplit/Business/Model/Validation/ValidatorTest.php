<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesSplit\Business\Model\Validation;

use Codeception\Test\Unit;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\SalesSplit\Business\Model\Validation\Messages;
use Spryker\Zed\SalesSplit\Business\Model\Validation\Validator;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SalesSplit
 * @group Business
 * @group Model
 * @group Validation
 * @group ValidatorTest
 * Add your own group annotations below this line
 */
class ValidatorTest extends Unit
{
    /**
     * @dataProvider invalidQuantityProvider
     *
     * @param int|float $quantity
     * @param int|float $quantityToSplit
     *
     * @return void
     */
    public function testInvalidQuantity($quantity, $quantityToSplit): void
    {
        $validator = $this->getValidator();
        $spySalesOrderItem = $this->getSalesOrderItem($quantity);

        $validateResponse = $validator->isValid($spySalesOrderItem, $quantityToSplit);
        $validationMessages = $validator->getMessages();

        $this->assertFalse($validateResponse);
        $this->assertEquals(Messages::VALIDATE_QUANTITY_MESSAGE, $validationMessages[0]);
    }

    /**
     * @return array
     */
    public function invalidQuantityProvider(): array
    {
        return [
            'int stock' => [1, 666],
            'float stock' => [2.8561, 700.64524],
        ];
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
        $discountCollection->append(new SpySalesDiscount());
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
     * @dataProvider validOrderItemProvider
     *
     * @param int|float $quantityToSplit
     *
     * @return void
     */
    public function testValidOrderItem($quantityToSplit): void
    {
        $validator = $this->getValidator();
        $spySalesOrderItem = $this->getSalesOrderItem();

        $validateResponse = $validator->isValid($spySalesOrderItem, $quantityToSplit);

        $this->assertTrue($validateResponse);
    }

    /**
     * @return array
     */
    public function validOrderItemProvider(): array
    {
        return [
            'int stock' => [1],
            'float stock' => [1.4],
            '0 < float stock < 1' => [0.3],
        ];
    }

    /**
     * @return \Spryker\Zed\SalesSplit\Business\Model\Validation\Validator
     */
    protected function getValidator()
    {
        return new Validator();
    }

    /**
     * @param int $quantity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function getSalesOrderItem($quantity = 2)
    {
        $spySalesOrderItem = new SpySalesOrderItem();
        $spySalesOrderItem->setQuantity($quantity);

        return $spySalesOrderItem;
    }
}
