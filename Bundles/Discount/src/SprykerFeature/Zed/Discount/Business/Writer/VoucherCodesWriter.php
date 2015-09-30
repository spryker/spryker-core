<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherCodesTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class VoucherCodesWriter extends AbstractWriter
{

    /**
     * @var DiscountWriter
     */
    protected $discountWriter;

    /**
     * @var DiscountVoucherPoolWriter
     */
    protected $discountVoucherPoolWriter;

    /**
     * @var DiscountVoucherPoolCategoryWriter
     */
    protected $discountVoucherPoolCategoryWriter;

    /**
     * @var DiscountDecisionRuleWriter
     */
    protected $discountDecisionRuleWriter;

    /**
     * @param DiscountQueryContainerInterface $queryContainer
     * @param DiscountWriter $discountWriter
     * @param DiscountVoucherPoolWriter $discountVoucherPoolWriter
     * @param DiscountVoucherPoolCategoryWriter $discountVoucherPoolCategoryWriter
     * @param DiscountDecisionRuleWriter $discountDecisionRuleWriter
     */
    public function __construct(
        DiscountQueryContainerInterface $queryContainer,
        DiscountWriter $discountWriter,
        DiscountVoucherPoolWriter $discountVoucherPoolWriter,
        DiscountVoucherPoolCategoryWriter $discountVoucherPoolCategoryWriter,
        DiscountDecisionRuleWriter $discountDecisionRuleWriter
    ) {
        $this->discountWriter = $discountWriter;
        $this->discountVoucherPoolWriter = $discountVoucherPoolWriter;
        $this->discountVoucherPoolCategoryWriter = $discountVoucherPoolCategoryWriter;
        $this->discountDecisionRuleWriter = $discountDecisionRuleWriter;

        parent::__construct($queryContainer);
    }

    /**
     * @param VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return VoucherPoolTransfer
     */
    public function saveVoucherCode(VoucherCodesTransfer $voucherCodesTransfer)
    {
        $voucherPoolCategory = $this->discountVoucherPoolCategoryWriter
            ->getOrCreateByName($voucherCodesTransfer->getVoucherPoolCategory())
        ;

        $voucherPool = $this->saveDiscountVoucherPool($voucherCodesTransfer, $voucherPoolCategory);
        $discount = $this->saveDiscount($voucherCodesTransfer, $voucherPool);
        $this->saveDiscountDecisionRules($voucherCodesTransfer, $discount);

        return (new VoucherPoolTransfer())->fromArray($voucherPool->toArray(), true);
    }

    /**
     * @param VoucherCodesTransfer $voucherCodesTransfer
     * @param DiscountTransfer $discountTransfer
     *
     * @return null
     */
    protected function saveDiscountDecisionRules(VoucherCodesTransfer $voucherCodesTransfer, DiscountTransfer $discountTransfer)
    {
        $decisionRules = $voucherCodesTransfer->getDecisionRules();
        if (count($decisionRules) < 1) {
            return null;
        }
        foreach ($decisionRules as $rule) {
            $decisionRuleTransfer = (new DecisionRuleTransfer())->fromArray($rule, true);
            $decisionRuleTransfer->setFkDiscount($discountTransfer->getIdDiscount());
            $decisionRuleTransfer->setName($discountTransfer->getDisplayName());
            $this->discountDecisionRuleWriter->saveDiscountDecisionRule($decisionRuleTransfer);
        }
    }

    /**
     * @param VoucherCodesTransfer $voucherCodesTransfer
     * @param $voucherPoolCategory
     *
     * @return SpyDiscountVoucherPool
     */
    protected function saveDiscountVoucherPool(VoucherCodesTransfer $voucherCodesTransfer, $voucherPoolCategory)
    {
        $voucherPoolTransfer = (new VoucherPoolTransfer())->fromArray($voucherCodesTransfer->toArray(), true);
        $voucherPoolTransfer->setFkDiscountVoucherPoolCategory($voucherPoolCategory->getIdDiscountVoucherPoolCategory());
        $voucherPool = $this->discountVoucherPoolWriter->save($voucherPoolTransfer);

        return $voucherPool;
    }

    /**
     * @param VoucherCodesTransfer $voucherCodesTransfer
     * @param $voucherPool
     *
     * @return DiscountTransfer
     */
    protected function saveDiscount(VoucherCodesTransfer $voucherCodesTransfer, $voucherPool)
    {
        $discountTransfer = $this->createDiscountTransfer($voucherCodesTransfer);
        $discountTransfer->setFkDiscountVoucherPool($voucherPool->getIdDiscountVoucherPool());
        $discountTransfer->setDisplayName($voucherCodesTransfer->getName());

        $spyDiscount = $this->discountWriter->save($discountTransfer);
        $discount = (new DiscountTransfer())->fromArray($spyDiscount->toArray(), true);

        return $discount;
    }

    /**
     * @param VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return DiscountTransfer
     */
    public function createDiscountTransfer(VoucherCodesTransfer $voucherCodesTransfer)
    {
        return (new DiscountTransfer())->fromArray($voucherCodesTransfer->toArray(), true);
    }
}
