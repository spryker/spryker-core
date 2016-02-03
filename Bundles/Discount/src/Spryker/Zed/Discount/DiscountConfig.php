<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount;

use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\Exception\MissingCalculatorException;
use Spryker\Zed\Discount\Business\Exception\MissingCollectorException;
use Spryker\Zed\Discount\Business\Exception\MissingDecisionRuleException;
use Spryker\Zed\Discount\Communication\Plugin\Collector\Aggregate;
use Spryker\Zed\Discount\Communication\Plugin\Collector\ItemProductOption;
use Spryker\Zed\Discount\Communication\Plugin\Collector\ItemExpense;
use Spryker\Zed\Discount\Communication\Plugin\Collector\OrderExpense;
use Spryker\Zed\Discount\Communication\Plugin\Collector\Item;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\Fixed;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\Percentage;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\MinimumCartSubtotal;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\Voucher;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DiscountConfig extends AbstractBundleConfig implements DiscountConfigInterface
{

    /**
     * @return array
     */
    public function getAvailableDecisionRulePlugins()
    {
        return [
            DiscountConstants::PLUGIN_DECISION_RULE_VOUCHER => new Voucher(),
            DiscountConstants::PLUGIN_DECISION_RULE_MINIMUM_CART_SUB_TOTAL => new MinimumCartSubtotal(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Model\CalculatorInterface[]
     */
    public function getAvailableCalculatorPlugins()
    {
        return [
            DiscountConstants::PLUGIN_CALCULATOR_PERCENTAGE => new Percentage(),
            DiscountConstants::PLUGIN_CALCULATOR_FIXED => new Fixed(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\CollectorInterface[]
     */
    public function getAvailableCollectorPlugins()
    {
        return [
            DiscountConstants::PLUGIN_COLLECTOR_ITEM => new Item(),
            DiscountConstants::PLUGIN_COLLECTOR_ORDER_EXPENSE => new OrderExpense(),
            DiscountConstants::PLUGIN_COLLECTOR_ITEM_EXPENSE => new ItemExpense(),
            DiscountConstants::PLUGIN_COLLECTOR_ITEM_PRODUCT_OPTION => new ItemProductOption(),
            DiscountConstants::PLUGIN_COLLECTOR_AGGREGATE => new Aggregate(),
        ];
    }

    /**
     * @throws \ErrorException
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface
     */
    public function getDefaultVoucherDecisionRulePlugin()
    {
        $availablePlugins = $this->getAvailableDecisionRulePlugins();

        if (!array_key_exists(DiscountConstants::PLUGIN_DECISION_RULE_VOUCHER, $availablePlugins)) {
            throw new \ErrorException('No default voucher decision rule plugin registered');
        }

        return $availablePlugins[DiscountConstants::PLUGIN_DECISION_RULE_VOUCHER];
    }

    /**
     * @param string $pluginName
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\MissingDecisionRuleException
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface
     */
    public function getDecisionRulePluginByName($pluginName)
    {
        $availableDecisionRules = $this->getAvailableDecisionRulePlugins();
        if (!isset($availableDecisionRules[$pluginName])) {
            throw new MissingDecisionRuleException(
                sprintf(
                    'Decision Rule Plugin %s could not be found, put it in DiscountConfig::getAvailableDecisionRulePlugins',
                    $pluginName
                )
            );
        }

        return $availableDecisionRules[$pluginName];
    }

    /**
     * @return array
     */
    public function getDecisionRulePluginNames()
    {
        return array_keys($this->getAvailableDecisionRulePlugins());
    }

    /**
     * @param string $pluginName
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\MissingCalculatorException
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName)
    {
        $availableCalculators = $this->getAvailableCalculatorPlugins();

        if (!isset($availableCalculators[$pluginName])) {
            throw new MissingCalculatorException(
                sprintf(
                    'Calculator Plugin %s could not be found, put it in DiscountConfig::getAvailableCalculatorPlugins',
                    $pluginName
                )
            );
        }

        return $availableCalculators[$pluginName];
    }

    /**
     * @param string $pluginName
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\MissingCollectorException
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface
     */
    public function getCollectorPluginByName($pluginName)
    {
        $availableCollectors = $this->getAvailableCollectorPlugins();
        if (!isset($availableCollectors[$pluginName])) {
            throw new MissingCollectorException(
                sprintf(
                    'Collector Plugin %s could not be found, put it in DiscountConfig::getAvailableCollectorPlugins',
                    $pluginName
                )
            );
        }

        return $availableCollectors[$pluginName];
    }

    /**
     * @return int
     */
    public function getVoucherCodeLength()
    {
        return DiscountConstants::DEFAULT_VOUCHER_CODE_LENGTH;
    }

    /**
     * @return array
     */
    public function getVoucherCodeCharacters()
    {
        return [
            DiscountConstants::KEY_VOUCHER_CODE_CONSONANTS => [
                'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z',
            ],
            DiscountConstants::KEY_VOUCHER_CODE_VOWELS => [
                'a', 'e', 'u',
            ],
            DiscountConstants::KEY_VOUCHER_CODE_NUMBERS => [
                1, 2, 3, 4, 5, 6, 7, 8, 9,
            ],
        ];
    }

    /**
     * @return int
     */
    public function getAllowedCodeCharactersLength()
    {
        $charactersLength = array_reduce($this->getVoucherCodeCharacters(), function ($length, $items) {
            $length += count($items);

            return $length;
        });

        return $charactersLength;
    }

    /**
     * @return string
     */
    public function getVoucherPoolTemplateReplacementString()
    {
        return '[code]';
    }

}
