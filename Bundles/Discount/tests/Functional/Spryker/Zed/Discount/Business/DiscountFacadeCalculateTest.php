<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Functional\Spryker\Zed\Discount\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class DiscountFacadeCalculateTest extends Test
{

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $discounts = SpyDiscountQuery::create()->find();
        foreach ($discounts as $discountEntity) {
            $discountEntity->setIsActive(false);
            $discountEntity->save();
        }
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function testCalculateWhenQueryStringMatchesAllItemsIncludeAllProvidedDiscounts()
    {
        $discountEntity = $this->createDiscountFixtures(
            '(sku = "123" or sku = "431")',
            'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"'
        );

        $quoteTransfer = $this->createQuoteTransfer();

        $discountFacade = new DiscountFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);

        $discountTransfer = $cartRuleDiscounts[0];
        $this->assertEquals($discountEntity->getAmount(), $discountTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateWithEmptyDecisionRuleShouldIncludeDiscount()
    {
        $discountEntity = $this->createDiscountFixtures(
            '',
            'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"'
        );

        $quoteTransfer = $this->createQuoteTransfer();

        $discountFacade = new DiscountFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(1, $cartRuleDiscounts);

        $discountTransfer = $cartRuleDiscounts[0];
        $this->assertEquals($discountEntity->getAmount(), $discountTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateWithIncorrectDecisionRuleShouldSkipDiscount()
    {
        $this->createDiscountFixtures(
            'alskdhas jkashdj asjkdhjashdjs ahjdhas1293820',
            'sku = "123" or sku is in "123' . ComparatorOperators::LIST_DELIMITER . '431"'
        );

        $quoteTransfer = $this->createQuoteTransfer();

        $discountFacade = new DiscountFacade();
        $quoteTransfer = $discountFacade->calculateDiscounts($quoteTransfer);

        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts();

        $this->assertCount(0, $cartRuleDiscounts);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setAbstractSku('123');
        $itemTransfer->setSku('123');
        $itemTransfer->setUnitGrossPrice(15000);

        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setAbstractSku('321');
        $itemTransfer->setSku('431');
        $itemTransfer->setUnitGrossPrice(1000);

        $quoteTransfer->addItem($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @param string $decisionRuleQueryString
     * @param string $collectorQueryString
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function createDiscountFixtures($decisionRuleQueryString, $collectorQueryString)
    {
        $discountEntity = new SpyDiscount();
        $discountEntity->setAmount(100);

        $discountEntity->setDecisionRuleQueryString($decisionRuleQueryString);
        $discountEntity->setCollectorQueryString($collectorQueryString);

        $discountEntity->setDisplayName('display name');
        $discountEntity->setCalculatorPlugin(DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED);
        $discountEntity->setDiscountType(DiscountConstants::TYPE_CART_RULE);
        $discountEntity->setIsActive(1);
        $discountEntity->setValidFrom(new \DateTime('yesterday'));
        $discountEntity->setValidTo(new \DateTime('tomorrow'));
        $discountEntity->save();

        return $discountEntity;
    }

}
