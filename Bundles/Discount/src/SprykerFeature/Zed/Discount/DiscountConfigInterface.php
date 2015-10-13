<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount;

use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;

interface DiscountConfigInterface
{

    const KEY_VOUCHER_CODE_CONSONANTS = 'consonants';
    const KEY_VOUCHER_CODE_VOWELS = 'vowels';
    const KEY_VOUCHER_CODE_NUMBERS = 'numbers';

    /**
     * @throws \ErrorException
     *
     * @return DiscountDecisionRulePluginInterface
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
     * @return DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName);

    /**
     * @param string $pluginName
     *
     * @return DiscountCollectorPluginInterface
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
