<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;

class PoolForm extends AbstractForm
{

    const NAME = 'name';
    const VOUCHER_POOL_CATEGORY = 'voucher_pool_category';
    const IS_INFINITELY_USABLE = 'is_infinitely_usable';
    const IS_ACTIVE = 'is_active';
    const IS_PRIVILEGED = 'is_privileged';
    const DESCRIPTION = 'description';
    const AMOUNT = 'amount';
    const AMOUNT_TYPE = 'type';
    const VALID_FROM = 'valid_from';
    const VALID_TO = 'valid_to';
    const DATE_NOW = 'now';
    const DATE_PERIOD_YEARS = 3;


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
     * @var Store
     */
    protected $store;

    /**
     * @param SpyDiscountVoucherPoolQuery $poolQuery
     * @param SpyDiscountQuery $discountQuery
     * @param int $idPool
     */
    public function __construct(SpyDiscountVoucherPoolQuery $poolQuery, SpyDiscountQuery $discountQuery, Store $store, $idPool)
    {
        $this->pool = $poolQuery->joinVoucherPoolCategory(SpyDiscountVoucherPoolCategoryTableMap::TABLE_NAME)
            ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, self::VOUCHER_POOL_CATEGORY)
            ->findOneByIdDiscountVoucherPool($idPool)
        ;
        $this->discount = $discountQuery->findOneByFkDiscountVoucherPool($idPool);
        $this->store = $store;
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
                'label' => 'Pool Category',
                'url' => '/discount/pool/category-suggest',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addTextarea(self::DESCRIPTION)
            ->addText(self::AMOUNT, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add(self::AMOUNT_TYPE, 'choice', [
                'label' => 'Value Type',
                'empty_value' => false,
                'choices' => [
                    SpyDiscountTableMap::COL_TYPE_FIXED => SpyDiscountTableMap::COL_TYPE_FIXED,
                    SpyDiscountTableMap::COL_TYPE_PERCENT => SpyDiscountTableMap::COL_TYPE_PERCENT,
                ]
            ])
            ->addDate(self::VALID_FROM, [
                'label' => 'Valid From',
            ])
            ->addDate(self::VALID_TO, [
                'label' => 'Valid Until'
            ])
            ->addCheckbox(self::IS_INFINITELY_USABLE, [
                'label' => 'Unlimited',
            ])
            ->addCheckbox(self::IS_PRIVILEGED, [
                'label' => 'Is Privileged',
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
            $validFrom = new \DateTime(
                self::DATE_NOW,
                new \DateTimeZone($this->store->getTimezone())
            );
            $validUntil = (new \DateTime(
                    self::DATE_NOW,
                    new \DateTimeZone($this->store->getTimezone())
                ))
                ->add(new \DateInterval('P' . self::DATE_PERIOD_YEARS . 'Y'))
            ;

            return [
                self::VALID_FROM => $validFrom,
                self::VALID_TO => $validUntil,
            ];
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
            $defaultData[self::VALID_FROM] = $this->discount->getValidFrom();
            $defaultData[self::VALID_TO] = $this->discount->getValidTo();
            $defaultData[self::DESCRIPTION] = $this->discount->getDescription();
            $defaultData[self::IS_PRIVILEGED] = $this->discount->getIsPrivileged();
        }

        return $defaultData;
    }

}
