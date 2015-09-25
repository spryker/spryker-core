<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class CartRuleType extends AbstractRuleType
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
    const FIELD_DECISION_RULES = 'decision_rules';

    const DATE_NOW = 'now';

    /**
     * @var array
     */
    protected $availableCalculatorPlugins;

    /**
     * @var array
     */
    protected $availableCollectorPlugins;

    /**
     * @var array
     */
    protected $availableDecisionRulePlugins;

    /**
     * @param array $availableCalculatorPlugins
     * @param array $availableCollectorPlugins
     * @param array $availableDecisionRulePlugins
     */
    public function __construct(array $availableCalculatorPlugins, array $availableCollectorPlugins, array $availableDecisionRulePlugins)
    {
        $this->availableCalculatorPlugins = $availableCalculatorPlugins;
        $this->availableCollectorPlugins = $availableCollectorPlugins;
        $this->availableDecisionRulePlugins = $availableDecisionRulePlugins;
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
            ->add(self::FIELD_AMOUNT, 'number', [
                'label' => 'Amount',
                'constraints' => [
                    new NotBlank(),
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
            ->add(self::FIELD_DECISION_RULES, 'collection', [
                'type' => new DecisionRuleType($this->availableDecisionRulePlugins),
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
        $availablePlugins = array_keys($this->availableCalculatorPlugins);

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
        $availablePlugins = array_keys($this->availableCollectorPlugins);

        foreach ($availablePlugins as $plugin) {
            $plugins[$plugin] = $this->filterChoicesLabels($plugin);
        }

        return $plugins;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cart_rule';
    }

}
