<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\PageObject;

use Codeception\Util\Locator;
use SprykerTest\Zed\Discount\DiscountPresentationTester;

class DiscountCreatePage
{
    public const URL = '/discount/index/create';

    public const MESSAGE_SUCCESSFUL_ALERT_CREATION = 'Discount successfully created, but not activated.';
    public const MESSAGE_SUCCESSFUL_ALERT_ACTIVATION = 'Discount successfully activated.';

    // DATA
    public const DISCOUNT_VALID_EXCLUSIVE = 'validExclusiveDiscount';
    public const EMPTY_DISCOUNT = 'emptyDiscount';
    public const DISCOUNT_VALID_NOT_EXCLUSIVE = 'validNotExclusiveDiscount';

    // Locators: Discount Tab
    public const BTN_CALCULATION_GET = '#btn-calculation-get';
    public const FIELD_DISCOUNT_QUERY = '#discount_discountCalculator_collector_query_string';
    public const DISCOUNT_CALCULATION_GROUP = '#builder_calculation_group_0';
    public const CURRENT_TAB = '.nav-tabs li.active';
    public const CURRENT_TAB_ERROR = '.nav-tabs li.active.error';

    /**
     * @var array
     */
    public $discountData = [
        self::DISCOUNT_VALID_EXCLUSIVE => [
            'type' => 'Cart rule',
            'name' => 'Exclusive Valid Discount',
            'description' => 'test test test',
            'excl' => '1',
            'calcType' => 'Calculator fixed',
            'amount' => '18,36',
            'applyTo' => 'attribute.width = \'15\'',
        ],
        self::EMPTY_DISCOUNT => [
            'type' => 'Cart rule',
            'name' => null,
            'description' => null,
            'excl' => null,
            'calcType' => 'Calculator fixed',
            'amount' => null,
            'applyTo' => null,
        ],
        self::DISCOUNT_VALID_NOT_EXCLUSIVE => [
            'type' => 'Cart rule',
            'name' => 'Not Exclusive Valid Discount',
            'description' => 'test test test',
            'excl' => '0',
            'calcType' => 'Calculator fixed',
            'amount' => '18,36',
            'applyTo' => 'attribute.width = \'15\'',
        ],
    ];

    /**
     * @var \SprykerTest\Zed\Discount\DiscountPresentationTester
     */
    protected $tester;

    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     */
    public function __construct(DiscountPresentationTester $i)
    {
        $this->tester = $i;
    }

    /**
     * @param string $dataTabId
     *
     * @return $this
     */
    public function tab(string $dataTabId)
    {
        $xpath = sprintf('//div[@class="tabs-container"]/ul/li[@data-tab-content-id="%s"]/a', $dataTabId);

        $this->tester->comment("At [$dataTabId] Tab");
        $this->tester->click($xpath);

        return $this;
    }

    /**
     * @return $this
     */
    public function open()
    {
        $this->tester->amOnPage(self::URL);

        return $this;
    }

    /**
     * @param string $discountName
     * @param array $override
     *
     * @return void
     */
    public function createDiscount($discountName, $override = [])
    {
        $i = $this->tester;
        $i->amZed();
        $i->amLoggedInUser();

        $dynamicData = [
            'name' => $this->discountData[$discountName]['name'] . ' ' . rand(1, 999),
            'validFrom' => '2016-01-01',
            'validTo' => date('Y-m-d', strtotime('tomorrow')),
            'dayNumber' => date('N'),
            'applyWhen' => 'day-of-week = \'' . date('N') . '\'',
        ];

        $this->open();

        $data = array_merge($this->discountData[$discountName], $dynamicData, $override);
        !$data['type'] ?: $i->selectOption('#discount_discountGeneral_discount_type', $data['type']);
        !$data['name'] ?: $i->fillField('#discount_discountGeneral_display_name', $data['name']);
        !$data['description'] ?: $i->fillField('#discount_discountGeneral_description', $data['description']);
        !$data['excl'] ?: $i->click('#discount_discountGeneral_is_exclusive_' . $data['excl']);
        !$data['validFrom'] ?: $i->fillField('#discount_discountGeneral_valid_from', $data['validFrom']);
        !$data['validTo'] ?: $i->fillField('#discount_discountGeneral_valid_to', $data['validTo']);

        $this->tab('tab-content-discount');
        !$data['calcType'] ?: $i->selectOption('#discount_discountCalculator_calculator_plugin', $data['calcType']);
        !$data['amount'] ?: $i->fillField('#discount_discountCalculator_moneyValueCollection_0_gross_amount', $data['amount']);
        $i->click(self::BTN_CALCULATION_GET);
        !$data['applyTo'] ?: $i->fillField(self::FIELD_DISCOUNT_QUERY, $data['applyTo']);

        $this->tab('tab-content-conditions');
        $i->click('#btn-condition-get');
        $i->fillField('#discount_discountCondition_decision_rule_query_string', $data['applyWhen']);
        $i->click('#create-discount-button');
    }

    /**
     * @param int $number
     * @param string $filter
     * @param string $operator
     * @param string $value
     *
     * @return void
     */
    public function fillInDiscountRule($number, $filter, $operator, $value)
    {
        $i = $this->tester;
        $i->waitForElement("select[name=builder_calculation_rule_{$number}_filter]");
        $i->selectOption("builder_calculation_rule_{$number}_filter", $filter);
        $i->selectOption("builder_calculation_rule_{$number}_operator", $operator);
        $i->fillField("builder_calculation_rule_{$number}_value_0", $value);
    }

    /**
     * @param string $operator
     * @param string $group
     *
     * @return void
     */
    public function changeDiscountGroupOperator($operator, $group = '0')
    {
        $this->tester->click(Locator::contains('label', $operator), "#builder_calculation_group_$group");
    }

    /**
     * @param string $query
     *
     * @return void
     */
    public function assertDiscountQuery($query)
    {
        $i = $this->tester;
        $i->click(self::BTN_CALCULATION_GET);
        $i->wait(2);
        $i->dontSeeElement(self::DISCOUNT_CALCULATION_GROUP);
        $i->seeElement(self::FIELD_DISCOUNT_QUERY);
        $i->seeInField(self::FIELD_DISCOUNT_QUERY, $query);
        $i->click(self::BTN_CALCULATION_GET);
        $i->waitForElementVisible(self::DISCOUNT_CALCULATION_GROUP);
    }
}
