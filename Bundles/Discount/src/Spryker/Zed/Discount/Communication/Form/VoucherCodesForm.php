<?php

namespace Spryker\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\VoucherCodesTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Zed\Discount\Communication\Form\Transformers\DecisionRulesFormTransformer;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\FormBuilderInterface;
use Zend\Filter\Word\CamelCaseToUnderscore;

class VoucherCodesForm extends AbstractRuleForm
{

    const FIELD_NAME = 'name';
    const FIELD_VOUCHER_POOL_CATEGORY = 'voucher_pool_category';
    const FIELD_IS_ACTIVE = 'is_active';
    const FIELD_IS_PRIVILEGED = 'is_privileged';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_AMOUNT = 'amount';
    const FIELD_VALID_FROM = 'valid_from';
    const FIELD_VALID_TO = 'valid_to';
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
     * @param CamelCaseToUnderscore $camelCaseToUnderscore
     * @param DiscountQueryContainer $discountQueryContainer
     */
    public function __construct(
        DiscountConfig $config,
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
        $idPool = $this->getRequest()->query->getInt(DiscountConstants::PARAM_ID_POOL);

        if ($idPool > 0) {
            $voucherCodesTransfer = $this->getVoucherCodesTransfer($idPool);

            return $voucherCodesTransfer->toArray();
        }

        return [
            self::FIELD_VALID_FROM => new \DateTime('now'),
            self::FIELD_VALID_TO => new \DateTime('now'),
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
     * @param int $idPool
     *
     * @return AbstractTransfer
     */
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
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_NAME, 'text', [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FIELD_VOUCHER_POOL_CATEGORY, new AutosuggestType(), [
                'label' => 'Pool Category',
                'url' => '/discount/pool/category-suggest',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FIELD_DESCRIPTION, 'textarea')
            ->add(self::FIELD_AMOUNT, 'text', [
                'label' => 'Amount (Please enter a valid amount. Eg. 5 or 5.55)',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintGreaterThan([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add(self::FIELD_VALID_FROM, 'date', [
                'label' => 'Valid From',
            ])
            ->add(self::FIELD_VALID_TO, 'date', [
                'label' => 'Valid Until',
            ])
            ->add(self::FIELD_IS_PRIVILEGED, 'checkbox', [
                'label' => 'Is Combinable with other discounts',
            ])
            ->add(self::FIELD_IS_ACTIVE, 'checkbox', [
                'label' => 'Active',
            ])
            ->add(self::FIELD_COLLECTOR_PLUGINS, 'collection', [
                'type' => new CollectorPluginForm($this->config),
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
                'type' => new DecisionRuleForm($this->config),
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
                'placeholder' => false,
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
