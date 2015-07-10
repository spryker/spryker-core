<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Dependency\Facade\DiscountFacadeInterface;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class VoucherPoolForm extends AbstractForm
{

    /**
     * @var DiscountQueryContainer
     */
    protected $queryContainer;

    /**
     * @var DiscountFacade
     */
    protected $discountFacade;

    /**
     * @param Request $request
     * @param QueryContainerInterface $queryContainer
     * @param DiscountFacadeInterface $discountFacade
     */
    public function __construct(
        Request $request,
        QueryContainerInterface $queryContainer,
        DiscountFacadeInterface $discountFacade
    ) {
        $this->discountFacade = $discountFacade;
        parent::__construct($request, $queryContainer);
    }

    /**
     * @return array|\SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field[]
     */
    public function addFormFields()
    {
        $this->addField('id_discount_voucher_pool')
            ->setConstraints([
                new Assert\Optional([
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ]),
            ]);

        $this->addField('fk_discount_voucher_pool_category')
            ->setAccepts($this->getVoucherPoolCategories())
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer',
                ]),
                new Assert\Choice([
                    'choices' => array_column($this->getVoucherPoolCategories(), 'value'),
                    'message' => 'Please choose one of the given Discount Categories',
                ]),
                new Assert\NotBlank(),
            ]);

        $this->addField('name')
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);

        $this->addField('template')
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);

        $this->addField('is_active')
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'boolean',
                ]),
            ]);

        $this->addField('is_infinitely_usable')
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'boolean',
                ]),
            ]);
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $idDiscountVoucherPool = $this->stateContainer->getRequestValue('id_discount_voucher_pool');
        $discountVoucherPoolEntity = $this->queryContainer->queryDiscount()->findPk($idDiscountVoucherPool);

        if ($discountVoucherPoolEntity) {
            return $discountVoucherPoolEntity->toArray();
        }

        return [
            'is_active' => true,
        ];
    }

    /**
     * @return array
     */
    protected function getVoucherPoolCategories()
    {
        $voucherPoolCategories = $this->queryContainer->queryDiscountVoucherPoolCategory()->find();

        if ($voucherPoolCategories->isEmpty()) {
            return [
                'value' => '',
                'label' => '',
            ];
        }

        $data = [];
        /** @var SpyDiscountVoucherPoolCategory $voucherPoolCategory */
        foreach ($voucherPoolCategories as $voucherPoolCategory) {
            $data[] = [
                'value' => $voucherPoolCategory->getPrimaryKey(),
                'label' => $voucherPoolCategory->getName(),
            ];
        }

        return $data;
    }

}
