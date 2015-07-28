<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

class PoolForm extends AbstractForm
{
    /**
     * @var SpyDiscountVoucherPoolCategory $poolCategory
     */
    protected $pool;

    /**
     * @param SpyDiscountVoucherPoolQuery $poolQuery
     * @param int $idPool
     */
    public function __construct(SpyDiscountVoucherPoolQuery $poolQuery, $idPool)
    {
        $this->pool = $poolQuery->findOneByIdDiscountVoucherPool($idPool);
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
//            ->addText('fk_discount_voucher_pool_category')
            ->addAutosuggest('fk_discount_voucher_pool_category', [
                'label' => 'Pool category',
                'url' => '/discount/pool/category-suggest'
            ])
            ->addCheckbox('is_infinitely_usable')
            ->addCheckbox('is_active')
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
        $name = ($this->pool instanceof SpyDiscountVoucherPool)
            ? $this->pool->getName()
            : ''
        ;

        return [
            'name' => $name,
        ];
    }

}
