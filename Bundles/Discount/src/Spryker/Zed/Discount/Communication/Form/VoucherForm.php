<?php

namespace Spryker\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Spryker\Zed\Discount\Communication\Form\Validators\MaximumCalculatedRangeValidator;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Discount\DiscountConfig;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class VoucherForm extends AbstractForm
{

    const ONE_VOUCHER = 1;
    const MINIMUM_VOUCHERS_TO_GENERATE = 2;

    const FIELD_DISCOUNT_VOUCHER_POOL = 'fk_discount_voucher_pool';
    const FIELD_QUANTITY = 'quantity';
    const FIELD_MAX_NUMBER_OF_USES = 'max_number_of_uses';
    const FIELD_CUSTOM_CODE = 'custom_code';
    const FIELD_CODE_LENGTH = 'code_length';

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainer
     */
    protected $discountQueryContainer;

    /**
     * @var bool
     */
    protected $isMultiple;

    /**
     * @var \Spryker\Zed\Discount\DiscountConfig
     */
    protected $discountConfig;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainer $discountQueryContainer
     * @param \Spryker\Zed\Discount\DiscountConfig $discountConfig
     * @param bool $isMultiple
     */
    public function __construct(DiscountQueryContainer $discountQueryContainer, DiscountConfig $discountConfig, $isMultiple = false)
    {
        $this->discountQueryContainer = $discountQueryContainer;
        $this->discountConfig = $discountConfig;
        $this->isMultiple = $isMultiple;
    }

    /**
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->isMultiple) {
            $builder->add(self::FIELD_QUANTITY, 'text', [
                'label' => 'Quantity',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                    $this->getConstraints()->createConstraintGreaterThan(1),
                ],
            ]);
        }

        $maxAllowedCodeCharactersLength = $this->discountConfig->getAllowedCodeCharactersLength();
        $codeLengthValidator = new MaximumCalculatedRangeValidator($maxAllowedCodeCharactersLength);

        $builder->add(self::FIELD_CUSTOM_CODE, 'text', [
            'label' => 'Custom Code',
            'attr' => [
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'Add [code] template to position generated code',
                'help' => 'Please enter a string that will be used as custom code, the string code can be used to put the code in a certain position, e.g. "summer-code-special"',
            ],
        ])
        ->add(self::FIELD_CODE_LENGTH, 'choice', [
            'label' => 'Random Generated Code Length',
            'choices' => $this->getCodeLengthChoices(),
            'constraints' => [
                $this->getConstraints()->createConstraintCallback([
                    'methods' => [
                        function ($length, ExecutionContextInterface $context) use ($codeLengthValidator) {
                            $formData = $context->getRoot()->getData();

                            if (empty($formData[self::FIELD_CUSTOM_CODE]) && $length < 1) {
                                $context->addViolation('Please add a custom code or select a length for code to be generated');

                                return;
                            }

                            if ($codeLengthValidator->getPossibleCodeCombinationsCount($length) < $formData[VoucherForm::FIELD_QUANTITY]) {
                                $context->addViolation('The quantity of required codes is to high regarding the code length');

                                return;
                            }
                        },
                    ],
                ]),
            ],
        ])
        ->add(self::FIELD_MAX_NUMBER_OF_USES, 'number', [
            'label' => 'Max number of uses (0 = Infinite usage)',
        ])
        ->add(self::FIELD_DISCOUNT_VOUCHER_POOL, 'choice', [
            'label' => 'Voucher',
            'placeholder' => 'Select one',
            'choices' => $this->getPools(),
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
        ]);
    }

    /**
     * @return array
     */
    protected function getPools()
    {
        $pools = [];
        $poolResult = $this->discountQueryContainer->queryVoucherPool()->find();

        if (!empty($poolResult)) {
            foreach ($poolResult as $discountVoucherPoolEntity) {
                $pools[$discountVoucherPoolEntity->getIdDiscountVoucherPool()] = $this->getDiscountVoucherPoolDisplayName($discountVoucherPoolEntity);
            }
        }

        return $pools;
    }

    /**
     * Set the values for fields
     *
     * @return array
     */
    public function populateFormFields()
    {
        return [
            static::FIELD_QUANTITY => ($this->isMultiple) ? static::MINIMUM_VOUCHERS_TO_GENERATE : static::ONE_VOUCHER,
        ];
    }

    /**
     * @return array
     */
    protected function getCodeLengthChoices()
    {
        $codeLengthChoices = [
            0 => 'No additional random characters',
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10,
        ];

        return $codeLengthChoices;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool $discountVoucherPoolEntity
     *
     * @return string
     */
    protected function getDiscountVoucherPoolDisplayName(SpyDiscountVoucherPool $discountVoucherPoolEntity)
    {
        $availableCalculatorPlugins = $this->discountConfig->getAvailableCalculatorPlugins();
        $displayName = $discountVoucherPoolEntity->getName();

        $discounts = [];
        foreach ($discountVoucherPoolEntity->getDiscounts() as $discountEntity) {
            $discountTransfer = new DiscountTransfer();
            $discountTransfer->fromArray($discountEntity->toArray(), true);

            /* @var DiscountCalculatorPluginInterface $calculator */
            $calculator = $availableCalculatorPlugins[$discountEntity->getCalculatorPlugin()];

            $discounts[] = $calculator->getFormattedAmount($discountTransfer);
        }

        if (!empty($discounts)) {
            $displayName .= ' (' . implode(', ', $discounts) . ')';
        }

        return $displayName;
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'voucher';
    }

}
