<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\VoucherCodesTransfer;
use SprykerFeature\Zed\Discount\Communication\Form\Transformers\DecisionRulesFormTransformer;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\FormBuilderInterface;
use Zend\Filter\Word\CamelCaseToUnderscore;

class VoucherCodesForm extends AbstractRuleForm
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
    const FIELD_COLLECTOR_LOGICAL_OPERATOR = 'collector_logical_operator';

    /**
     * @var array
     */
    protected $availablePoolCategories;

    /**
     * @var DiscountConfig
     */
    protected $config;

    /**
     * @var CamelCaseToUnderscore
     */
    protected $camelCaseToUnderscore;

    /**
     * @var DiscountQueryContainer
     */
    protected $discountQueryContainer;

    /**
     * @param DiscountConfig $config
     * @param array $availablePoolCategories
     * @param CamelCaseToUnderscore $camelCaseToUnderscore
     */
    public function __construct(
        DiscountConfig $config,
//        array $availablePoolCategories,
        CamelCaseToUnderscore $camelCaseToUnderscore,
        DiscountQueryContainer $discountQueryContainer
    ) {
        parent::__construct(
            $config->getAvailableCalculatorPlugins(),
            $config->getAvailableCollectorPlugins(),
            $config->getAvailableDecisionRulePlugins()
        );

        $this->config = $config;
        $this->camelCaseToUnderscore = $camelCaseToUnderscore;
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $idPool = $this->getRequest()->query->getInt(DiscountConfig::PARAM_ID_POOL);

        if ($idPool > 0) {
            $voucherCodesTransfer = $this->getVoucherCodesTransfer($idPool);

            return $voucherCodesTransfer->toArray();
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
     * @return void
     */
    protected function getDataClass()
    {
    }

    protected function getVoucherCodesTransfer($idPool)
    {
        $discountVoucherPoolEntity = $this->discountQueryContainer->queryVoucherCodeByIdVoucherCode($idPool)->findOne();

        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->filterByFkDiscountVoucherPool($idPool)
            ->findOne();

        $decisionRuleEntities = $discountEntity->getDecisionRules();
        $discountCollectorEntities = $discountEntity->getDiscountCollectors();
        $discountVoucherPool = $discountVoucherPoolEntity->toArray();
        $discountVoucherPool[CartRuleForm::FIELD_COLLECTOR_PLUGINS] = $discountCollectorEntities->toArray();

        $voucherCodesTransfer = (new VoucherCodesTransfer())->fromArray($discountVoucherPool, true);
        $voucherCodesTransfer->setDecisionRules($decisionRuleEntities->toArray());
        $voucherCodesTransfer->setCalculatorPlugin($discountEntity->getCalculatorPlugin());

        $voucherCodesTransfer->setIsPrivileged((bool) $discountEntity->getIsPrivileged());
        $voucherCodesTransfer->setValidFrom($discountEntity->getValidFrom());
        $voucherCodesTransfer->setValidTo($discountEntity->getValidTo());

        return $voucherCodesTransfer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::VOUCHER_POOL_CATEGORY, new AutosuggestType(), [
                'label' => 'Pool Category',
                'url' => '/discount/pool/category-suggest',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::DESCRIPTION, 'textarea')
            ->add(self::AMOUNT, 'text', [
                'label' => 'Amount (Please enter a valid amount. Eg. 5 or 5.55)',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintGreaterThan([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add(self::VALID_FROM, 'date', [
                'label' => 'Valid From',
            ])
            ->add(self::VALID_TO, 'date', [
                'label' => 'Valid Until',
            ])
            ->add(self::IS_PRIVILEGED, 'checkbox', [
                'label' => 'Is Combinable with other discounts',
            ])
            ->add(self::IS_ACTIVE, 'checkbox', [
                'label' => 'Active',
            ])
            ->add(self::FIELD_COLLECTOR_PLUGINS, 'collection', [
                'type' => new CollectorPluginForm($this->config->getAvailableCollectorPlugins()),
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
            ->add(self::FIELD_DECISION_RULES, 'collection', [
                'type' => new DecisionRuleForm($this->config->getAvailableDecisionRulePlugins()),
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
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->addModelTransformer(new DecisionRulesFormTransformer($this->config, $this->camelCaseToUnderscore));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'voucher_codes';
    }

}
