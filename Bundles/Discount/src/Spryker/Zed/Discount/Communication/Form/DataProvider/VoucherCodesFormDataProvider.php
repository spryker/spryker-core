<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Generated\Shared\Transfer\VoucherCodesTransfer;
use Spryker\Zed\Discount\Communication\Form\CartRuleForm;
use Spryker\Zed\Discount\Communication\Form\VoucherCodesForm;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;

class VoucherCodesFormDataProvider
{

    /**
     * @var DiscountQueryContainer
     */
    protected $discountQueryContainer;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainer $discountQueryContainer
     */
    public function __construct(DiscountQueryContainer $discountQueryContainer)
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
                'rule_1' => [
                    'value' => '',
                    'rules' => '',
                ],
            ],
            VoucherCodesForm::FIELD_COLLECTOR_PLUGINS => [
                'plugin_1' => [
                    'collector_plugin' => '',
                    'value' => '',
                ],
            ],
//            'group' => [],
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

        $voucherCodesTransfer->setIsPrivileged((bool) $discountEntity->getIsPrivileged());
        $voucherCodesTransfer->setValidFrom($discountEntity->getValidFrom());
        $voucherCodesTransfer->setValidTo($discountEntity->getValidTo());

        return $voucherCodesTransfer;
    }

}
