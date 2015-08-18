<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;

class VoucherForm extends AbstractForm
{

    const FIELD_POOL = 'pool';
    const FIELD_NUMBER = 'number';
    const MINIMUM_VOUCHERS_TO_GENERATE = 2;
    const ONE_VOUCHER = 1;

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
     */
    public function __construct(SpyDiscountVoucherPoolQuery $poolQuery, $isMultiple=false)
    {
        $this->poolQuery = $poolQuery;
        $this->isMultiple = $isMultiple;
    }

    /**
     * Prepares form
     *
     * @return VoucherForm
     */
    protected function buildFormFields()
    {
        if ($this->isMultiple) {
            $this
                ->addText(static::FIELD_NUMBER, [
                    'label' => 'Number',
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThan(1)
                    ],
                ])
            ;
        }

        $this
            ->addChoice(static::FIELD_POOL, [
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
     * @return $this
     */
    protected function populateFormFields()
    {
        return [
            static::FIELD_NUMBER => ($this->isMultiple) ? static::MINIMUM_VOUCHERS_TO_GENERATE : static::ONE_VOUCHER,
        ];
    }

}
