<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount;

interface DiscountConfigInterface
{

    /**
     * @throws \ErrorException
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface
     */
    public function getDefaultVoucherDecisionRulePlugin();

    /**
     * @param string $pluginName
     *
     * @return mixed
     */
    public function getDecisionRulePluginByName($pluginName);

    /**
     * @param string $pluginName
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName);

    /**
     * @param string $pluginName
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface
     */
    public function getCollectorPluginByName($pluginName);

    /**
     * @return array
     */
    public function getVoucherCodeCharacters();

    /**
     * @return int
     */
    public function getVoucherCodeLength();

    /**
     * @return string
     */
    public function getVoucherPoolTemplateReplacementString();

    /**
     * @return int
     */
    public function getAllowedCodeCharactersLength();

}
