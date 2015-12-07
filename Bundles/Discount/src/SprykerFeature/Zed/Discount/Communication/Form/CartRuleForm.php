<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\DiscountConfig;
use Symfony\Component\Form\FormBuilderInterface;

class CartRuleForm extends AbstractRuleForm
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
    const FIELD_COLLECTOR_PLUGINS = 'collector_plugins';
    const FIELD_DECISION_RULES = 'decision_rules';
    const FIELD_COLLECTOR_LOGICAL_OPERATOR = 'collector_logical_operator';

    const VALID_FROM = 'valid_from';
    const VALID_TO = 'valid_to';

    const DATE_NOW = 'now';

    /**
     * @var DiscountFacade
     */
    protected $discountFacade;

    public function __construct(DiscountConfig $config, DiscountFacade $discountFacade)
    {
        parent::__construct(
            $config->getAvailableCalculatorPlugins(),
            $config->getAvailableCollectorPlugins(),
            $config->getAvailableDecisionRulePlugins()
        );

        $this->discountFacade = $discountFacade;
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $idDiscount = $this->getRequest()->query->getInt(DiscountConfig::PARAM_ID_DISCOUNT);

        if ($idDiscount > 0) {
            $cartRuleDefaultData = $this->discountFacade->getCurrentCartRulesDetailsByIdDiscount($idDiscount);

            return $cartRuleDefaultData;
        }

        return [
            self::VALID_FROM => new \DateTime('now'),
            self::VALID_TO => new \DateTime('now'),
            'decision_rules' => [
                'rule_1' => [
                    'value' => '',
                    'rules' => '',
                ],
            ],
            'collector_plugins' => [
                'plugin_1' => [
                    'collector_plugin' => '',
                    'value' => '',
                ],
            ],
            'group' => [],
        ];
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_DISPLAY_NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FIELD_DESCRIPTION, 'textarea')
            ->add(self::FIELD_AMOUNT, 'text', [
                'label' => 'Amount',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FIELD_CALCULATOR_PLUGIN, 'choice', [
                'label' => 'Calculator Plugin',
                'choices' => $this->getAvailableCalculatorPlugins(),
                'empty_data' => null,
                'required' => false,
                'placeholder' => false,
            ])
            ->add(self::FIELD_COLLECTOR_PLUGINS, 'collection', [
                'type' => new CollectorPluginForm($this->availableCollectorPlugins),
                'label' => null,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true,
            ])
            ->add(self::FIELD_COLLECTOR_LOGICAL_OPERATOR, 'choice', [
                'label' => 'Logical operator for combining multiple collectors',
                'choices' => $this->getCollectorLogicalOperators(),
                'required' => true,
            ])
            ->add(self::FIELD_VALID_FROM, 'date')
            ->add(self::FIELD_VALID_TO, 'date')
            ->add(self::FIELD_IS_PRIVILEGED, 'checkbox', [
                'label' => 'Is Combinable with other discounts',
            ])
            ->add(self::FIELD_IS_ACTIVE, 'checkbox', [
                'label' => 'Is Active',
            ])
            ->add(self::FIELD_DECISION_RULES, 'collection', [
                'type' => new DecisionRuleForm($this->availableDecisionRulePlugins),
                'label' => null,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true,
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cart_rule';
    }

}
