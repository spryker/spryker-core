<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Pyz\Shared\Validator\Constraints\NotBlank;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
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
    const FIELD_CALCULATOR_PLUGIN = 'calculator_plugin';
    const FIELD_COLLECTOR_PLUGIN = 'collector_plugin';

    const DATE_NOW = 'now';
    const DATE_PERIOD_YEARS = 3;

    const FIELD_DECISION_RULE_PLUGIN = 'decision_rule_plugin';
    const FIELD_DECISION_RULE_VALUE = 'value';

    const DECISION_RULES_PREFIX = 'PLUGIN_DECISION_RULE_';
    const DECISION_COLLECTOR_PREFIX = 'PLUGIN_COLLECTOR_';

    /**
     * @var SpyDiscountQuery
     */
    protected $discountQuery;

    /**
     * @var SpyDiscountDecisionRuleQuery
     */
    protected $decisionRuleQuery;

    /**
     * @var DiscountConfig
     */
    protected $discountConfig;

    /**
     * @var Store
     */
    protected $store;

    protected $options = [
        'allow_extra_fields' => true,
    ];

    /**
     * @param SpyDiscountQuery $discountQuery
     * @param DiscountConfig $discountConfig
     * @param SpyDiscountDecisionRuleQuery $decisionRuleQuery
     */
    public function __construct(SpyDiscountQuery $discountQuery,
        DiscountConfig $discountConfig,
        SpyDiscountDecisionRuleQuery $decisionRuleQuery,
        Store $store
    ) {
        $this->discountQuery = $discountQuery;
        $this->decisionRuleQuery = $decisionRuleQuery;
        $this->discountConfig = $discountConfig;
        $this->store = $store;
    }

    protected function buildFormFields()
    {
        $this->options['allow_extra_fields'] = true;

//        dump($this);
//        die;

        $this
            ->addText(self::FIELD_DISPLAY_NAME, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addTextarea(self::FIELD_DESCRIPTION)
            ->addText(self::FIELD_AMOUNT, [
                'label' => 'Amount',
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                    ])
                ]
            ])
            ->addChoice(self::FIELD_CALCULATOR_PLUGIN, [
                'label' => 'Collector Plugin',
                'choices' => $this->getAvailableCalculatorPlugins(),
                'empty_data' => null,
                'required' => false,
                'placeholder' => 'Default',
            ])
            ->addChoice(self::FIELD_COLLECTOR_PLUGIN, [
                'label' => 'Collector Plugin',
                'choices' => $this->getAvailableCollectorPlugins(),
            ])
            ->addChoice(self::FIELD_TYPE, [
                'label' => 'Value Type',
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    SpyDiscountTableMap::COL_TYPE_FIXED => SpyDiscountTableMap::COL_TYPE_FIXED,
                    SpyDiscountTableMap::COL_TYPE_PERCENT => SpyDiscountTableMap::COL_TYPE_PERCENT,
                ],
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

//            ->add('rules', 'collection', array(
//                'type'   => 'choice',
//                'label' => 'Rules',
//                'data' => [],
//                'options'  => array(
//                    'choices'  => array(
//                        'nashville' => 'Nashville',
//                        'paris'     => 'Paris',
//                        'berlin'    => 'Berlin',
//                        'london'    => 'London',
//                    ),
//                ),
//            ));

            ->addCollection('decision_rules', $this->buildRulesCollection())
            ->addCollection('decision_rules_values', $this->buildRulesValuesCollection())

//            ->addSelect(self::FIELD_DECISION_RULE_PLUGIN, [
//                'label' => 'Decision Rule',
//                'choices' => $this->getDecisionRuleOptions(),
//            ])
//            ->addNumber(self::FIELD_DECISION_RULE_VALUE, [
//                'constraints' => [
//                    new GreaterThan([
//                        'value' => 0,
//                    ])
//                ]
//            ])
        ;
    }

    protected function buildRulesCollection()
    {
        return [
            'type' => 'text',
            'data' => [
                'a' => 'string 1',
                'b' => 'string 2',
                'c' => 'string 3',
            ],
        ];
    }

    protected function buildRulesValuesCollection()
    {
        return [
            'type' => 'text',
            'data' => [
                'a' => 'str 1',
                'b' => 'str 2',
                'c' => 'str 3',
            ],
        ];
    }

    protected function getAvailableCalculatorPlugins()
    {
        $plugins = [];
        $availablePlugins = array_keys($this->discountConfig->getAvailableCalculatorPlugins());

        foreach ($availablePlugins as $plugin) {
            $plugins[$plugin] = $this->filterChoicesLabels($plugin);
        }

        return $plugins;
    }

    protected function getAvailableCollectorPlugins()
    {
        $plugins = [];
        $availablePlugins = array_keys($this->discountConfig->getAvailableCollectorPlugins());

        foreach ($availablePlugins as $plugin) {
            $plugins[$plugin] = $this->filterChoicesLabels($plugin);
        }

        return $plugins;
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
     * @return array
     */
    protected function populateFormFields()
    {
        $discount = $this->discountQuery->findOne();
        if (null === $discount) {
            $validFrom = new \DateTime(
                self::DATE_NOW,
                new \DateTimeZone($this->store->getTimezone())
            );
            $validUntil = (new \DateTime(
                self::DATE_NOW,
                new \DateTimeZone($this->store->getTimezone())
            ))
                ->add(new \DateInterval('P' . self::DATE_PERIOD_YEARS . 'Y'))
            ;

            return [
                self::FIELD_VALID_FROM => $validFrom,
                self::FIELD_VALID_TO => $validUntil,
            ];
        }

        $decisionRule = $discount->getDecisionRules()[0];

        $defaultData = [
            self::FIELD_DISPLAY_NAME => $discount->getDisplayName(),
            self::FIELD_DESCRIPTION => $discount->getDescription(),
            self::FIELD_AMOUNT => (int) $discount->getAmount(),
            self::FIELD_TYPE => $discount->getType(),
            self::FIELD_VALID_FROM => $discount->getValidFrom(),
            self::FIELD_VALID_TO => $discount->getValidTo(),
            self::FIELD_IS_PRIVILEGED => $discount->getIsPrivileged(),
            self::FIELD_IS_ACTIVE => $discount->getIsActive(),
            self::FIELD_DECISION_RULE_PLUGIN => $decisionRule->getDecisionRulePlugin(),
            self::FIELD_DECISION_RULE_VALUE => $decisionRule->getValue(),
        ];

        return $defaultData;
    }

}
