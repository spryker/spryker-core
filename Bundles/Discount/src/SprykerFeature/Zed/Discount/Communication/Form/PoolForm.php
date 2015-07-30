<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Validator\Constraints\NotBlank;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;

class PoolForm extends AbstractForm
{

    const NAME = 'name';
    const VOUCHER_POOL_CATEGORY = 'voucher_pool_category';
    const IS_INFINITELY_USABLE = 'is_infinitely_usable';
    const IS_ACTIVE = 'is_active';
    const AMOUNT = 'amount';
    const AMOUNT_TYPE = 'type';

    /**
     * @var SpyDiscountVoucherPoolCategory
     */
    protected $pool;

    /**
     * @var SpyDiscount
     */
    protected $discount;

    /**
     * @var int
     */
    protected $idPool;

    /**
     * @param SpyDiscountVoucherPoolQuery $poolQuery
     * @param SpyDiscountQuery $discountQuery
     * @param int $idPool
     */
    public function __construct(SpyDiscountVoucherPoolQuery $poolQuery, SpyDiscountQuery $discountQuery, $idPool)
    {
        $this->pool = $poolQuery->joinVoucherPoolCategory(SpyDiscountVoucherPoolCategoryTableMap::TABLE_NAME)
            ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, self::VOUCHER_POOL_CATEGORY)
            ->findOneByIdDiscountVoucherPool($idPool)
        ;
        $this->discount = $discountQuery->findOneByFkDiscountVoucherPool($idPool);
        $this->idPool = $idPool;
    }

    /**
     * @return PoolForm
     */
    protected function buildFormFields()
    {
        $this
            ->addText(self::NAME, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addAutosuggest(self::VOUCHER_POOL_CATEGORY, [
                'label' => 'Pool category',
                'url' => '/discount/pool/category-suggest',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addText(self::AMOUNT, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
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
    protected function populateFormFields()
    {
        if (!$this->pool instanceof SpyDiscountVoucherPool) {
            return [];
        }

        $defaultData = [
            self::NAME => $this->pool->getName(),
            self::VOUCHER_POOL_CATEGORY => $this->pool->getVirtualColumn(self::VOUCHER_POOL_CATEGORY),
            self::IS_ACTIVE => $this->pool->getIsActive(),
            self::IS_INFINITELY_USABLE => $this->pool->getIsInfinitelyUsable(),
        ];

        if ($this->discount instanceof SpyDiscount) {
            $defaultData[self::AMOUNT] = $this->discount->getAmount();
            $defaultData[self::AMOUNT_TYPE] = $this->discount->getType();
        }

        return $defaultData;
    }

}
