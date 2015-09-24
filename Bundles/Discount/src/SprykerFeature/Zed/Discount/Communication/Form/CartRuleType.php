<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Pyz\Shared\Validator\Constraints\NotBlank;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @todo CD-474 refactor Form Generator
 */
class CartRuleType extends AbstractType
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
    const FIELD_CART_RULES = 'cart_rules';

    const DATE_NOW = 'now';
    const DATE_PERIOD_YEARS = 3;

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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_DISPLAY_NAME, 'text', [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add(self::FIELD_DESCRIPTION, 'textarea')
            ->add(self::FIELD_AMOUNT, 'text', [
                'label' => 'Amount',
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                    ])
                ]
            ])
            ->add(self::FIELD_TYPE, 'choice', [
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
            ->add(self::FIELD_CALCULATOR_PLUGIN, 'choice', [
                'label' => 'Collector Plugin',
                'choices' => $this->getAvailableCalculatorPlugins(),
                'empty_data' => null,
                'required' => false,
                'placeholder' => 'Default',
            ])
            ->add(self::FIELD_COLLECTOR_PLUGIN, 'choice', [
                'label' => 'Collector Plugin',
                'choices' => $this->getAvailableCollectorPlugins(),
            ])
            ->add(self::FIELD_VALID_FROM, 'date')
            ->add(self::FIELD_VALID_TO, 'date')
            ->add(self::FIELD_IS_PRIVILEGED, 'checkbox', [
                'label' => 'Is Combinable',
            ])
            ->add(self::FIELD_IS_ACTIVE, 'checkbox', [
                'label' => 'Is Active',
            ])
            ->add('cart_rules', 'collection', [
                'type' => new DecisionRuleType($this->discountConfig),
                'label' => null,
                'allow_add' => true,
                'allow_extra_fields' => true,
            ])
        ;
    }

    /**
     * @return array
     */
    protected function getAvailableCalculatorPlugins()
    {
        $plugins = [];
        $availablePlugins = array_keys($this->discountConfig->getAvailableCalculatorPlugins());

        foreach ($availablePlugins as $plugin) {
            $plugins[$plugin] = $this->filterChoicesLabels($plugin);
        }

        return $plugins;
    }

    /**
     * @return array
     */
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
        return 'cart_rule';
    }

}
