<?php

namespace Spryker\Zed\Discount\Communication\Table;

use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Application\Business\Url\Url;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountsTable extends AbstractTable
{

    const COL_VALUE = 'Value';
    const COL_PERIOD = 'Period';
    const COL_OPTIONS = 'Options';
    const DATE_FORMAT = 'Y-m-d';
    const COL_DECISION_RULES = 'Cart Rules';
    const DECISION_RULE_PLUGIN = 'DecisionRulePlugin';

    const PARAM_ID_DISCOUNT = 'id-discount';
    const URL_DISCOUNT_CART_RULE_EDIT = '/discount/cart-rule/edit';

    /**
     * @var \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    protected $discountQuery;

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountQuery $discountQuery
     */
    public function __construct(SpyDiscountQuery $discountQuery)
    {
        $this->discountQuery = $discountQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyDiscountTableMap::COL_ID_DISCOUNT => 'ID',
            SpyDiscountTableMap::COL_DISPLAY_NAME => 'Display Name',
            SpyDiscountTableMap::COL_DESCRIPTION => 'Description',
            self::COL_VALUE => self::COL_VALUE,
            SpyDiscountTableMap::COL_IS_PRIVILEGED => 'Is Privileged',
            SpyDiscountTableMap::COL_IS_ACTIVE => 'Is Active',
            self::COL_PERIOD => self::COL_PERIOD,
            self::COL_DECISION_RULES => self::COL_DECISION_RULES,
            self::COL_OPTIONS => self::COL_OPTIONS,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $result = [];

        $query = $this->discountQuery
            ->where('fk_discount_voucher_pool IS NULL');

        $queryResult = $this->runQuery($query, $config, true);
        /** @var \Orm\Zed\Discount\Persistence\SpyDiscount $item */
        foreach ($queryResult as $item) {
            $chosenDecisionRules = array_column($item->getDecisionRules()->toArray(), self::DECISION_RULE_PLUGIN);

            $result[] = [
                SpyDiscountTableMap::COL_ID_DISCOUNT => $item->getIdDiscount(),
                SpyDiscountTableMap::COL_DISPLAY_NAME => $item->getDisplayName(),
                SpyDiscountTableMap::COL_DESCRIPTION => $item->getDescription(),
                self::COL_VALUE => $this->getDiscountPrice($item),
                SpyDiscountTableMap::COL_IS_PRIVILEGED => $item->getIsPrivileged(),
                SpyDiscountTableMap::COL_IS_ACTIVE => $item->getIsActive(),
                self::COL_PERIOD => $item->getValidFrom(self::DATE_FORMAT) . ' - ' . $item->getValidTo(self::DATE_FORMAT),
                self::COL_DECISION_RULES => implode(', ', $chosenDecisionRules),
                self::COL_OPTIONS => implode(' ', $this->getRowOptions($item)),
            ];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discount
     *
     * @return string
     */
    protected function getDiscountPrice(SpyDiscount $discount)
    {
        $amount = $discount->getAmount();
        $amountType = $this->getDiscountAmountType($discount);

        return $amount . ' ' . $amountType;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discount
     *
     * @return string
     */
    protected function getDiscountAmountType(SpyDiscount $discount)
    {
        if ($discount->getCalculatorPlugin() === DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE) {
            return 'percentage';
        }

        return 'fixed';
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $item
     *
     * @return array
     */
    protected function getRowOptions(SpyDiscount $item)
    {
        $options = [];
        $options[] = $this->generateEditButton(
            Url::generate(self::URL_DISCOUNT_CART_RULE_EDIT, [
                self::PARAM_ID_DISCOUNT => $item->getIdDiscount(),
            ]),
            'Edit'
        );

        return $options;
    }

}
