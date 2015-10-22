<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Communication\Form\Validators\MaximumCalculatedRangeValidator;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
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
     * @var SpyDiscountVoucherPoolQuery
     */
    protected $poolQuery;

    /**
     * @var bool
     */
    protected $isMultiple;

    /**
     * @var DiscountConfig
     */
    protected $discountConfig;

    /**
     * @param SpyDiscountVoucherPoolQuery $poolQuery
     * @param DiscountConfig $discountConfig
     * @param bool $isMultiple
     */
    public function __construct(SpyDiscountVoucherPoolQuery $poolQuery, DiscountConfig $discountConfig, $isMultiple = false)
    {
        $this->poolQuery = $poolQuery;
        $this->discountConfig = $discountConfig;
        $this->isMultiple = $isMultiple;
    }

    /**
     * Prepares form
     *
     * @return self
     */
    protected function buildFormFields()
    {
        if ($this->isMultiple) {
            $this
                ->addText(self::FIELD_QUANTITY, [
                    'label' => 'Quantity',
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThan(1),
                    ],
                ])
            ;
        }

        $maxAllowedCodeCharactersLength = $this->discountConfig->getAllowedCodeCharactersLength();
        $codeLengthValidator = new MaximumCalculatedRangeValidator($maxAllowedCodeCharactersLength);

        $this
            ->addText(self::FIELD_CUSTOM_CODE, [
                'label' => 'Custom Code',
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => 'Add [code] template to position generated code',
                ],
            ])
            ->add(self::FIELD_CODE_LENGTH, 'choice', [
                'label' => 'Random Generated Code Length',
                'choices' => $this->getCodeLengthChoices(),
                'constraints' => [
                    new Callback([
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
                ]
            ])
            ->addNumber(self::FIELD_MAX_NUMBER_OF_USES, [
                'label' => 'Max number of uses (0 = Infinite usage)',
            ])
            ->addChoice(self::FIELD_DISCOUNT_VOUCHER_POOL, [
                'label' => 'Pool',
                'placeholder' => 'Select one',
                'choices' => $this->getPools(),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }

    /**
     * @return array
     */
    private function getPools()
    {
        $pools = [];
        $poolResult = $this->poolQuery->find()->toArray();

        if (!empty($poolResult)) {
            foreach ($poolResult as $item) {
                $pools[$item['IdDiscountVoucherPool']] = $item['Name'];
            }
        }

        return $pools;
    }

    /**
     * Set the values for fields
     *
     * @return array
     */
    protected function populateFormFields()
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

}
