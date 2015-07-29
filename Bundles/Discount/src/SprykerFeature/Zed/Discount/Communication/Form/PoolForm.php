<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Form\Extension\Core\Type\RadioType;

class PoolForm extends AbstractForm
{

    const NAME = 'name';
    const VOUCHER_POOL_CATEGORY = 'voucher_pool_category';
    const IS_INFINITELY_USABLE = 'is_infinitely_usable';
    const IS_ACTIVE = 'is_active';
    const AMOUNT = 'amount';
    const AMOUNT_TYPE = 'type';

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
            ->addText(self::NAME)
            ->addAutosuggest(self::VOUCHER_POOL_CATEGORY, [
                'label' => 'Pool category',
                'url' => '/discount/pool/category-suggest'
            ])
            ->addText(self::AMOUNT)
            ->add(self::AMOUNT_TYPE, 'choice', [
                'label' => 'Value type',
                'empty_value' => false,
                'choices' => [
                    SpyDiscountTableMap::COL_TYPE_FIXED => SpyDiscountTableMap::COL_TYPE_FIXED,
                    SpyDiscountTableMap::COL_TYPE_PERCENT => SpyDiscountTableMap::COL_TYPE_PERCENT,
                ]
            ])
            ->addCheckbox(self::IS_INFINITELY_USABLE, [
                'label' => 'Unlimited',
            ])
            ->addCheckbox(self::IS_ACTIVE, [
                'label' => 'Active',
            ])
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
            self::NAME => $name,
//            self::VALUE_TYPE => false,
        ];
    }

}
