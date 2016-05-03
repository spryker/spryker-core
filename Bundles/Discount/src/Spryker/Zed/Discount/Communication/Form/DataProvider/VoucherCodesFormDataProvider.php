<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Generated\Shared\Transfer\VoucherCodesTransfer;
use Spryker\Zed\Discount\Communication\Form\CartRuleForm;
use Spryker\Zed\Discount\Communication\Form\VoucherCodesForm;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class VoucherCodesFormDataProvider
{

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @param int|null $idPool
     *
     * @return array
     */
    public function getData($idPool = null)
    {
        if ($idPool > 0) {
            $voucherCodesTransfer = $this->getVoucherCodesTransfer($idPool);

            return $voucherCodesTransfer->toArray();
        }

        return [
            VoucherCodesForm::FIELD_VALID_FROM => new \DateTime('now'),
            VoucherCodesForm::FIELD_VALID_TO => new \DateTime('now'),
            VoucherCodesForm::FIELD_DECISION_RULES => [
                [
                    'value' => '',
                    'rules' => '',
                ],
            ],
            VoucherCodesForm::FIELD_COLLECTOR_PLUGINS => [
                [
                    'collector_plugin' => '',
                    'value' => '',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @param int $idPool
     *
     * @return \Generated\Shared\Transfer\VoucherCodesTransfer
     */
    protected function getVoucherCodesTransfer($idPool)
    {
        $discountVoucherPoolEntity = $this->discountQueryContainer->queryVoucherCodeByIdVoucherCode($idPool)->findOne();

        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->filterByFkDiscountVoucherPool($idPool)
            ->findOne();

        $decisionRuleEntities = $discountEntity->getDecisionRules();
        $discountCollectorEntities = $discountEntity->getDiscountCollectors();
        $discountVoucherPool = $discountVoucherPoolEntity->toArray();
        $discountVoucherPool[CartRuleForm::FIELD_COLLECTOR_PLUGINS] = $discountCollectorEntities->toArray();

        $voucherCodesTransfer = new VoucherCodesTransfer();
        $voucherCodesTransfer->fromArray($discountVoucherPool, true);

        $voucherCodesTransfer->setDecisionRules($decisionRuleEntities->toArray());
        $voucherCodesTransfer->setCalculatorPlugin($discountEntity->getCalculatorPlugin());

        $voucherCodesTransfer->setIsPrivileged((bool)$discountEntity->getIsPrivileged());
        $voucherCodesTransfer->setValidFrom($discountEntity->getValidFrom());
        $voucherCodesTransfer->setValidTo($discountEntity->getValidTo());

        return $voucherCodesTransfer;
    }

}
