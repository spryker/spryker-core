<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Pyz\Shared\Validator\Constraints\NotBlank;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Required;

class DecisionRuleType extends AbstractType
{

    const FIELD_DECISION_RULE_PLUGIN = 'decision_rule_plugin';
    const FIELD_VALUE = 'value';

    const DECISION_RULES_PREFIX = 'PLUGIN_DECISION_RULE_';
    const DECISION_COLLECTOR_PREFIX = 'PLUGIN_COLLECTOR_';

    /**
     * @var DiscountConfig
     */
    protected $discountConfig;

    /**
     * @param DiscountConfig $discountConfig
     */
    public function __construct(DiscountConfig $discountConfig)
    {
        $this->discountConfig = $discountConfig;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_DECISION_RULE_PLUGIN, 'choice', [
                'label' => 'Decision Rule',
                'multiple' => false,
                'choices' => $this->getDecisionRuleOptions(),
                'constraints' => [
                    new Required(),
                ],
            ])
            ->add(self::FIELD_VALUE, 'text', [
                'label' => 'Amount',
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                    ])
                ]
            ])
        ;
    }

    /**
     * @return array
     */
    protected function getDecisionRuleOptions()
    {
        $decisionRules = [];
        $decisionRulesKeys = array_keys($this->discountConfig->getAvailableDecisionRulePlugins());

        foreach ($decisionRulesKeys as $key) {
            $decisionRules[$key] = $this->filterChoicesLabels($key);
        }

        return $decisionRules;
    }

    /**
     * @param string $decisionRuleName
     *
     * @return string
     */
    protected function filterChoicesLabels($decisionRuleName)
    {
        $decisionRuleName = str_replace(
            [self::DECISION_RULES_PREFIX, self::DECISION_COLLECTOR_PREFIX, '_'],
            ['', '', ' '],
            $decisionRuleName
        );

        return mb_convert_case($decisionRuleName, MB_CASE_TITLE, "UTF-8");
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'decision_rule';
    }

}
