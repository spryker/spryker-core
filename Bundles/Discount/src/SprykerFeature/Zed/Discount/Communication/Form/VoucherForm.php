<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;

class VoucherForm extends AbstractForm
{
    const FIELD_ID_POOL = 'id_voucher_pool';
    const FIELD_QUANTITY = 'quantity';
    const MINIMUM_VOUCHERS_TO_GENERATE = 2;
    const ONE_VOUCHER = 1;
    const FIELD_MAX_NUMBER_OF_USES = 'max_number_of_uses';

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
                ->addText(static::FIELD_QUANTITY, [
                    'label' => 'Quantity',
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThan(1)
                    ],
                ])
            ;
        }

        $this
            ->addNumber(static::FIELD_MAX_NUMBER_OF_USES, [
                'label' => 'Max number of uses',
            ])
            ->addChoice(static::FIELD_ID_POOL, [
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

}
