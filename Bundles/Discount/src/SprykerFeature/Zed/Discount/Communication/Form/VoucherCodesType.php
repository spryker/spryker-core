<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Communication\Form\Transformers\DecisionRulesFormTransformer;
use SprykerFeature\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Zend\Filter\Word\CamelCaseToUnderscore;

class VoucherCodesType extends AbstractRuleType
{

    const NAME = 'name';
    const VOUCHER_POOL_CATEGORY = 'voucher_pool_category';
    const IS_ACTIVE = 'is_active';
    const IS_PRIVILEGED = 'is_privileged';
    const DESCRIPTION = 'description';
    const AMOUNT = 'amount';
    const VALID_FROM = 'valid_from';
    const VALID_TO = 'valid_to';
    const DATE_NOW = 'now';
    const DATE_PERIOD_YEARS = 3;

    const FIELD_CALCULATOR_PLUGIN = 'calculator_plugin';
    const FIELD_COLLECTOR_PLUGINS = 'collector_plugins';
    const FIELD_DECISION_RULES = 'decision_rules';

    /**
     * @var array
     */
    protected $availablePoolCategories;

    /**
     * @var CamelCaseToUnderscore
     */
    protected $camelCaseToUnderscoreFilter;
    /**
     * @var CamelCaseToUnderscore
     */
    private $camelCaseToUnderscore;

    /**
     * @param array                 $availableCalculatorPlugins
     * @param array                 $availableCollectorPlugins
     * @param array                 $availableDecisionRulePlugins
     * @param array                 $availablePoolCategories
     * @param CamelCaseToUnderscore $camelCaseToUnderscore
     */
    public function __construct(
        array $availableCalculatorPlugins,
        array $availableCollectorPlugins,
        array $availableDecisionRulePlugins,
        array $availablePoolCategories,
        CamelCaseToUnderscore $camelCaseToUnderscore
    ) {
        parent::__construct($availableCalculatorPlugins, $availableCollectorPlugins, $availableDecisionRulePlugins);

        $this->availablePoolCategories = $availablePoolCategories;
        $this->camelCaseToUnderscore = $camelCaseToUnderscore;

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::NAME, 'text', [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add(self::VOUCHER_POOL_CATEGORY, new AutosuggestType(), [
                'label' => 'Pool Category',
                'url' => '/discount/pool/category-suggest',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add(self::DESCRIPTION, 'textarea')
            ->add(self::AMOUNT, 'text', [
                'label' => 'Amount (Please enter a valid amount. Eg. 5 or 5.55)',
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add(self::VALID_FROM, 'date', [
                'label' => 'Valid From',
            ])
            ->add(self::VALID_TO, 'date', [
                'label' => 'Valid Until'
            ])
            ->add(self::IS_PRIVILEGED, 'checkbox', [
                'label' => 'Is Privileged',
            ])
            ->add(self::IS_ACTIVE, 'checkbox', [
                'label' => 'Active',
            ])
            ->add(self::FIELD_COLLECTOR_PLUGINS, 'collection', [
                'type' => new CollectorPluginType($this->availableCollectorPlugins),
                'label' => null,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true,
            ])
            ->add(self::FIELD_DECISION_RULES, 'collection', [
                'type' => new DecisionRuleType($this->availableDecisionRulePlugins),
                'label' => null,
                'allow_add' => true,
                'allow_delete' => true,
                'allow_extra_fields' => true,
            ])
            ->add(self::FIELD_CALCULATOR_PLUGIN, 'choice', [
                'label' => 'Calculator Plugin',
                'choices' => $this->getAvailableCalculatorPlugins(),
                'empty_data' => null,
                'required' => false,
                'placeholder' => 'Default',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addModelTransformer(new DecisionRulesFormTransformer($this->camelCaseToUnderscore))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'voucher_codes';
    }

}
