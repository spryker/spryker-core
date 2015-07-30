<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\NotBlank;

class VoucherForm extends AbstractForm
{

    const FIELD_POOL = 'pool';

    /**
     * @var SpyDiscountVoucherPoolQuery
     */
    protected $poolQuery;

    /**
     * @param SpyDiscountVoucherPoolQuery $poolQuery
     */
    public function __construct(SpyDiscountVoucherPoolQuery $poolQuery)
    {
        $this->poolQuery = $poolQuery;
    }

    /**
     * Prepares form
     *
     * @return VoucherForm
     */
    protected function buildFormFields()
    {
        $this
            ->addChoice(self::FIELD_POOL, [
                'label' => 'Pool',
                'placeholder' => 'Select one',
                'choices' => $this->getPolls(),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }

    /**
     * @return array
     */
    public function getPolls()
    {
        $poolResult = $this->poolQuery->find()->toArray();

        $pools = [];

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
        return [];
    }

}
