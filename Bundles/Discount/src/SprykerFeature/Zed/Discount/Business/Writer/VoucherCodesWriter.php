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
     * @var DiscountCollectorWriter
     */
    private $discountCollectorWriter;

    /**
     * @param DiscountQueryContainerInterface   $queryContainer
     * @param DiscountWriter                    $discountWriter
     * @param DiscountVoucherPoolWriter         $discountVoucherPoolWriter
     * @param DiscountVoucherPoolCategoryWriter $discountVoucherPoolCategoryWriter
     * @param DiscountDecisionRuleWriter        $discountDecisionRuleWriter
     * @param DiscountCollectorWriter           $discountCollectorWriter
     */
    public function __construct(
        DiscountQueryContainerInterface $queryContainer,
        DiscountWriter $discountWriter,
        DiscountVoucherPoolWriter $discountVoucherPoolWriter,
        DiscountVoucherPoolCategoryWriter $discountVoucherPoolCategoryWriter,
        DiscountDecisionRuleWriter $discountDecisionRuleWriter,
        DiscountCollectorWriter $discountCollectorWriter
    ) {
        parent::__construct($queryContainer);

        $this->discountWriter = $discountWriter;
        $this->discountVoucherPoolWriter = $discountVoucherPoolWriter;
        $this->discountVoucherPoolCategoryWriter = $discountVoucherPoolCategoryWriter;
        $this->discountDecisionRuleWriter = $discountDecisionRuleWriter;
        $this->discountCollectorWriter = $discountCollectorWriter;
    }

    /**
     * @param VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return VoucherPoolTransfer
     */
    public function saveVoucherCode(VoucherCodesTransfer $voucherCodesTransfer)
    {
        $voucherPoolCategoryEntity = $this->discountVoucherPoolCategoryWriter
            ->getOrCreateByName($voucherCodesTransfer->getVoucherPoolCategory());

        $voucherPoolEntity = $this->saveDiscountVoucherPool($voucherCodesTransfer, $voucherPoolCategoryEntity);
        $discountEntity = $this->saveDiscount($voucherCodesTransfer, $voucherPoolEntity);
        $this->saveDiscountDecisionRules($voucherCodesTransfer, $discountEntity);

        foreach ($voucherCodesTransfer->getCollectorPlugins() as $collectorPluginTransfer) {
            $collectorPluginTransfer->setFkDiscount($discountEntity->getIdDiscount());
            $this->discountCollectorWriter->save($collectorPluginTransfer);
        }

        return (new VoucherPoolTransfer())->fromArray($voucherPoolEntity->toArray(), true);
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
