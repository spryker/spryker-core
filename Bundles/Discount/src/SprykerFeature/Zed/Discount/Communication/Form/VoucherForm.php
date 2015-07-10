<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Generated\Zed\Ide\FactoryAutoCompletion\DiscountCommunication;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Dependency\Facade\DiscountFacadeInterface;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class VoucherForm extends AbstractForm
{

    const ID_DISCOUNT_VOUCHER = 'id_discount_voucher';
    const FK_DISCOUNT_VOUCHER_POOL = 'fk_discount_voucher_pool';
    const CODE = 'code';
    const IS_ACTIVE = 'is_active';

    /**
     * @var DiscountQueryContainer
     */
    protected $queryContainer;

    /**
     * @var DiscountFacade
     */
    protected $discountFacade;

    /**
     * @var DiscountCommunication
     */
    protected $factory;

    /**
     * @param Request $request
     * @param DiscountQueryContainerInterface $queryContainer
     * @param DiscountFacadeInterface $discountFacade
     * @param FactoryInterface $factory
     */
    public function __construct(
        Request $request,
        DiscountQueryContainerInterface $queryContainer,
        DiscountFacadeInterface $discountFacade,
        FactoryInterface $factory
    ) {
        $this->discountFacade = $discountFacade;
        $this->factory = $factory;
        parent::__construct($request, $queryContainer);
    }

    /**
     * @return array|\SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field[]
     */
    public function addFormFields()
    {
        $this->addField(self::ID_DISCOUNT_VOUCHER)
            ->setConstraints([
                new Assert\Optional([
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ]),
            ]);

        $this->addField(self::FK_DISCOUNT_VOUCHER_POOL)
            ->setAccepts($this->getVoucherPools())
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer',
                ]),
                new Assert\Choice([
                    'choices' => array_column($this->getVoucherPools(), 'value'),
                    'message' => 'Please choose one of the given Voucher Pools',
                ]),
                new Assert\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            });

        $this->addField(self::CODE)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
                $this->factory->createConstraintCodeExists(
                    $this->queryContainer,
                    $this->getIdDiscount()
                ),
            ]);

        $this->addField(self::IS_ACTIVE)
            ->setConstraints([
                new Assert\Type(
                    ['type' => 'boolean']
                ),
            ]);
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $idDiscountVoucher = $this->stateContainer->getRequestValue(self::ID_DISCOUNT_VOUCHER);
        $discountVoucherEntity = $this->queryContainer->queryDiscountVoucher()->findPk($idDiscountVoucher);

        if ($discountVoucherEntity) {
            return $discountVoucherEntity->toArray();
        }

        return [
            self::IS_ACTIVE => true,
        ];
    }

    /**
     * @return array
     */
    protected function getVoucherPools()
    {
        $voucherPools = $this->queryContainer->queryVoucherPool()->find();

        $data = [];
        /** @var SpyDiscountVoucherPool $voucherPool */
        foreach ($voucherPools as $voucherPool) {
            $data[] = [
                'value' => $voucherPool->getPrimaryKey(),
                'label' => $voucherPool->getName(),
            ];
        }

        return $data;
    }

    /**
     * @return int
     */
    protected function getIdDiscount()
    {
        return $this->stateContainer->getRequestValue(self::ID_DISCOUNT_VOUCHER);
    }

}
