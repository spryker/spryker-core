<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;

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
     * @param SpyDiscountVoucherPoolQuery $poolQuery
     * @param bool $isMultiple
     */
    public function __construct(SpyDiscountVoucherPoolQuery $poolQuery, $isMultiple = false)
    {
        $this->poolQuery = $poolQuery;
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
                        new GreaterThan(1)
                    ],
                ])
            ;
        }

        $this
            ->addText(self::FIELD_CUSTOM_CODE, [
                'label' => 'Custom Code',
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => 'Add [code] template to position generated code'
                ],
            ])
            ->add(self::FIELD_CODE_LENGTH, 'choice', [
                'label' => 'Random Generated Code Length',
                'choices' => $this->getCodeLenghtChoices(),
            ])
            ->addNumber(self::FIELD_MAX_NUMBER_OF_USES, [
                'label' => 'Max number of uses',
            ])
            ->addChoice(static::FIELD_DISCOUNT_VOUCHER_POOL, [
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
    protected function getCodeLenghtChoices()
    {
        $codeLengthChoices = [
            0 => 'No extra random characters',
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
