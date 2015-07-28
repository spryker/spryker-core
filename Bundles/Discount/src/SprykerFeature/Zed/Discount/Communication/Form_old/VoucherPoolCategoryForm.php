<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\Validator\Constraints as Assert;

class VoucherPoolCategoryForm extends AbstractForm
{

    /**
     * @var DiscountQueryContainer
     */
    protected $queryContainer;

    /**
     * @return array|\SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field[]
     */
    public function addFormFields()
    {
        $this->addField('id_discount_voucher_pool_category')
            ->setConstraints([
                new Assert\Optional([
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ]),
            ]);

        $this->addField('name')
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $idDiscountVoucherPoolCategory = $this->stateContainer->getRequestValue('id_discount_voucher_pool_category');
        $discountVoucherPoolCategoryEntity = $this->queryContainer->queryDiscountVoucherPoolCategory()
                                                ->findPk($idDiscountVoucherPoolCategory);

        if ($discountVoucherPoolCategoryEntity) {
            return $discountVoucherPoolCategoryEntity->toArray();
        }

        return [
            'name' => '',
        ];
    }

}
