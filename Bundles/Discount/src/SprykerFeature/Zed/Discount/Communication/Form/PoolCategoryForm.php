<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategoryQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

class PoolCategoryForm extends AbstractForm
{
    /**
     * @var SpyDiscountVoucherPoolCategory $poolCategory
     */
    protected $poolCategory;

    /**
     * @param SpyDiscountVoucherPoolCategoryQuery $poolCategoryQuery
     * @param int $idPoolCategory
     */
    public function __construct(SpyDiscountVoucherPoolCategoryQuery $poolCategoryQuery, $idPoolCategory)
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

    /**
     * @return array
     */
    private function getValidity()
    {
        $vouchers = [];

        for ($i=3; $i<=20; $i++) {
            $vouchers[$i] = $i . ' Years';
        }

        return $vouchers;
    }

    /**
     * @return array
     */
    private function getPolls()
    {
        return [
            'alfa',
            'beta',
        ];
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $name = ($this->poolCategory instanceof SpyDiscountVoucherPoolCategory)
            ? $this->poolCategory->getName()
            : ''
        ;

        return [
            'name' => $name,
        ];
    }

}
