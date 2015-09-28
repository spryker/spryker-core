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

    public function saveVoucherCode(VoucherCodesTransfer $voucherCodesTransfer)
    {
        // save discount voucher pool category
        $voucherPoolCategory = $this->discountVoucherPoolCategoryWriter
            ->getOrCreateByName($voucherCodesTransfer->getVoucherPoolCategory())
        ;

        // save discount voucher pool
        $voucherPoolTransfer = (new VoucherPoolTransfer())->fromArray($voucherCodesTransfer->toArray(), true);
        $voucherPoolTransfer->setFkDiscountVoucherPoolCategory($voucherPoolCategory->getIdDiscountVoucherPoolCategory());
        $voucherPool = $this->discountVoucherPoolWriter->save($voucherPoolTransfer);

        // save discount
        $discountTransfer = $this->createDiscountTransfer($voucherCodesTransfer);
        $discountTransfer->setFkDiscountVoucherPool($voucherPool->getIdDiscountVoucherPool());
        $discountTransfer->setDisplayName($voucherCodesTransfer->getName());
        $spyDiscount = $this->discountWriter->save($discountTransfer);
        $discount = (new DiscountTransfer())->fromArray($spyDiscount->toArray(), true);

        // save discount decision rule
        $this->saveDiscountDecisionRules($voucherCodesTransfer, $discount);

        return (new VoucherPoolTransfer())->fromArray($voucherPool->toArray(), true);
    }

    protected function saveDiscountDecisionRules(VoucherCodesTransfer $voucherCodesTransfer, DiscountTransfer $discountTransfer)
    {
        $decisionRules = $voucherCodesTransfer->getDecisionRules();
        if (count($decisionRules) > 0) {
            foreach ($decisionRules as $rule) {
                $decisionRuleTransfer = (new DecisionRuleTransfer())->fromArray($rule, true);
                $decisionRuleTransfer->setFkDiscount($discountTransfer->getIdDiscount());
                $this->discountDecisionRuleWriter->saveDiscountDecisionRule($decisionRuleTransfer);
            }
        }
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

    public function createVoucherCode(VoucherCodesTransfer $voucherCodesTransfer)
    {
        throw new \Exception('not implemented yet');
    }

    public function updateVoucherCode(VoucherCodesTransfer $voucherCodesTransfer)
    {
        throw new \Exception('not implemented yet');
    }

}
