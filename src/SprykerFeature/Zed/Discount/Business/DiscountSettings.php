<?php

namespace SprykerFeature\Zed\Discount\Business;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;

class DiscountSettings implements
    DiscountSettingsInterface
{
    /**
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @var DiscountDecisionRulePluginInterface[]
     */
    protected $decisionRulePlugins = [];

    /**
     * @var DiscountCalculatorPluginInterface[]
     */
    protected $calculatorPlugins = [];

    /**
     * @var DiscountCollectorPluginInterface[]
     */
    protected $collectorPlugins = [];

    /**
     * @param LocatorLocatorInterface $locator
     * @param array $decisionRulePlugins
     * @param array $calculatorPlugins
     * @param array $collectorPlugins
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        array $decisionRulePlugins,
        array $calculatorPlugins,
        array $collectorPlugins
    ) {
        $this->locator = $locator;
        $this->decisionRulePlugins = $decisionRulePlugins;
        $this->calculatorPlugins = $calculatorPlugins;
        $this->collectorPlugins = $collectorPlugins;
    }

    /**
     * @return DiscountDecisionRulePluginInterface
     * @throws \ErrorException
     */
    public function getDefaultVoucherDecisionRulePlugin()
    {
        if (! array_key_exists(DiscountDependencyContainer::PLUGIN_DECISION_RULE_VOUCHER, $this->decisionRulePlugins)) {
            throw new \ErrorException('No default voucher decision rule plugin registered');
        }

        return $this->decisionRulePlugins[DiscountDependencyContainer::PLUGIN_DECISION_RULE_VOUCHER];
    }

    /**
     * @param string $pluginName
     * @return DiscountDecisionRulePluginInterface
     */
    public function getDecisionRulePluginByName($pluginName)
    {
        return $this->decisionRulePlugins[$pluginName];
    }

    /**
     * @return array
     */
    public function getDecisionPluginNames()
    {
        return array_keys($this->decisionRulePlugins);
    }

    /**
     * @param string $pluginName
     * @return DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName)
    {
        return $this->calculatorPlugins[$pluginName];
    }

    /**
     * @param string $pluginName
     * @return DiscountCollectorPluginInterface
     */
    public function getCollectorPluginByName($pluginName)
    {
        return $this->collectorPlugins[$pluginName];
    }

    /**
     * @return int
     */
    public function getVoucherCodeLength()
    {
        return 6;
    }

    /**
     * @return array
     */
    public function getVoucherCodeCharacters()
    {
        return [
            self::KEY_VOUCHER_CODE_CONSONANTS => [
                'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
            ],
            self::KEY_VOUCHER_CODE_VOWELS => [
                'a', 'e', 'u'
            ],
            self::KEY_VOUCHER_CODE_NUMBERS => [
                1, 2, 3, 4, 5, 6, 7, 8, 9
            ],
            self::KEY_VOUCHER_CODE_SPECIAL_CHARACTERS => [
                '-'
            ]
        ];
    }

    /**
     * @return string
     */
    public function getVoucherPoolTemplateReplacementString()
    {
        return '[code]';
    }
}
