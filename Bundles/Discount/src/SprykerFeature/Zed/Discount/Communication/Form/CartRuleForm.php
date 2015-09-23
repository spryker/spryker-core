<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Pyz\Shared\Validator\Constraints\NotBlank;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRuleQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Required;

class CartRuleForm extends AbstractForm
{

    const FIELD_DISPLAY_NAME = 'display_name';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_AMOUNT = 'amount';
    const FIELD_TYPE = 'type';
    const FIELD_VALID_FROM = 'valid_from';
    const FIELD_VALID_TO = 'valid_to';
    const FIELD_IS_PRIVILEGED = 'is_privileged';
    const FIELD_IS_ACTIVE = 'is_active';

    const FIELD_DECISION_RULE_PLUGIN = 'decision_rule_plugin';
    const FIELD_DECISION_RULE_VALUE = 'value';

    const DECISION_RULES_PREFIX = 'PLUGIN_DECISION_RULE_';

    protected $discountQuery;

    protected $decisionRuleQuery;

    protected $discountConfig;

    public function __construct(SpyDiscountQuery $discountQuery, DiscountConfig $discountConfig, SpyDiscountDecisionRuleQuery $decisionRuleQuery)
    {
        $this->discountQuery = $discountQuery;
        $this->decisionRuleQuery = $decisionRuleQuery;
        $this->discountConfig = $discountConfig;
    }

    protected function buildFormFields()
    {
        $this
            ->addText(self::FIELD_DISPLAY_NAME, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addTextarea(self::FIELD_DESCRIPTION)
            ->addInteger(self::FIELD_AMOUNT, [
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                    ])
                ]
            ])
            ->addChoice(self::FIELD_TYPE, [
                'label' => 'Value Type',
                'multiple' => false,
                'expanded' => true,
                'choices' => $this->getDiscountTypeSelectChoices(),
                'constraints' => [
                    new Required(),
                ],
            ])
            ->addDate(self::FIELD_VALID_FROM)
            ->addDate(self::FIELD_VALID_TO)
            ->addCheckbox(self::FIELD_IS_PRIVILEGED, [
                'label' => 'Is Combinable',
            ])
            ->addCheckbox(self::FIELD_IS_ACTIVE, [
                'label' => 'Is Active',
            ])
            ->addSelect(self::FIELD_DECISION_RULE_PLUGIN, [
                'label' => 'Decision Rule',
                'choices' => $this->getDecisionRuleOptions(),
            ])
            ->addNumber(self::FIELD_DECISION_RULE_VALUE, [
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                    ])
                ]
            ])
        ;
    }

    protected function getDecisionRuleOptions()
    {
        $decisionRules = [];
        $decisionRulesKeys = array_keys($this->discountConfig->getAvailableDecisionRulePlugins());

        foreach ($decisionRulesKeys as $key) {
            $decisionRules[$key] = $this->filterDecisionRuleName($key);
        }

        return $decisionRules;
    }

    protected function filterDecisionRuleName($decisionRuleName)
    {
        $decisionRuleName = str_replace([self::DECISION_RULES_PREFIX, '_'], ['', ' '], $decisionRuleName);

        return ucfirst(strtolower($decisionRuleName));
    }

    protected function getDiscountTypeSelectChoices()
    {
        $options = SpyDiscountTableMap::getValueSet(SpyDiscountTableMap::COL_TYPE);

        $choices = [];
        foreach ($options as $option) {
            $choices[$option] = $option;
        }

        return $choices;
    }

    protected function getSelectedType(SpyDiscount $discount)
    {
        return array_search($discount->getType(), SpyDiscountTableMap::getValueSet(SpyDiscountTableMap::COL_TYPE));
    }

    protected function populateFormFields()
    {
        $discount = $this->discountQuery->findOne();
        if (null === $discount) {
            return [];
        }

        $discountType = $this->getSelectedType($discount);
        $defaultData = [
            self::FIELD_DISPLAY_NAME => $discount->getDisplayName(),
            self::FIELD_DESCRIPTION => $discount->getDescription(),
            self::FIELD_AMOUNT => $discount->getAmount(),
            self::FIELD_TYPE => ($discountType) ?: null,
            self::FIELD_VALID_FROM => $discount->getValidFrom(),
            self::FIELD_VALID_TO => $discount->getValidTo(),
            self::FIELD_IS_PRIVILEGED => $discount->getIsPrivileged(),
            self::FIELD_IS_ACTIVE => $discount->getIsActive(),
        ];

        return $defaultData;
    }

}
