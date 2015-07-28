<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategoryQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

class PoolCategoryForm extends AbstractForm
{
    protected $poolCategory;

    public function __constructor(SpyDiscountVoucherPoolCategoryQuery $poolCategoryQuery, $idPoolCategory)
    {
        $this->poolCategory = $poolCategoryQuery->findOneByIdDiscountVoucherPoolCategory($idPoolCategory);
    }

    /**
     * Prepares form
     *
     * @return $this
     */
    protected function buildFormFields()
    {
        $this
            ->addText('name')
        ;
    }

    private function getValidity()
    {
        $vouchers = [];

        for ($i=3; $i<=20; $i++) {
            $vouchers[$i] = $i . ' Years';
        }

        return $vouchers;
    }

    private function getPolls()
    {
        return [
            'alfa',
            'beta',
        ];
    }

    /**
     * Set the values for fields
     *
     * @return $this
     */
    protected function populateFormFields()
    {
        // @TODO: Implement populateFormFields() method.
    }

}
