<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategoryQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

class PoolCategoryForm extends AbstractForm
{

    const COL_NAME = 'name';

    /**
     * @var SpyDiscountVoucherPoolCategory
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
     * @return self
     */
    protected function buildFormFields()
    {
        $this
            ->addText(self::COL_NAME, [
                'constraints' => [
                    $this->locateConstraint()->createConstraintNotBlank(),
                ],
            ])
        ;
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
            self::COL_NAME => $name,
        ];
    }

}
