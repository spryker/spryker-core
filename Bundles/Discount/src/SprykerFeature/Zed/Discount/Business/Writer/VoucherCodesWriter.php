<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherCodesTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;

class VoucherCodesWriter extends AbstractWriter
{

    const COLLECTOR_PLUGINS = 'collector_plugins';
    const ID_DISCOUNT_COLLECTOR = 'id_discount_collector';
    const ID_DISCOUNT_DECISION_RULE = 'id_discount_decision_rule';

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
        $discountTransfer = $this->saveDiscount($voucherCodesTransfer, $voucherPoolEntity);

        $this->saveDiscountDecisionRules($voucherCodesTransfer, $discountTransfer);
        $this->saveDiscountsCollectorPlugins($voucherCodesTransfer, $discountTransfer);

        return (new VoucherPoolTransfer())->fromArray($voucherPoolEntity->toArray(), true);
    }

    /**
     * @param VoucherCodesTransfer $voucherCodesTransfer
     * @param DiscountTransfer $discountTransfer
     *
     * @return void
     */
    protected function saveDiscountsCollectorPlugins(VoucherCodesTransfer $voucherCodesTransfer, DiscountTransfer $discountTransfer)
    {
        $this->deleteCollectorPlugins($voucherCodesTransfer);

        foreach ($voucherCodesTransfer->getCollectorPlugins() as $collectorPluginTransfer) {
            if (is_array($collectorPluginTransfer)) {
                $collectorPluginTransfer = (new DiscountCollectorTransfer())->fromArray($collectorPluginTransfer, true);
            }

            $collectorPluginTransfer->setFkDiscount($discountTransfer->getIdDiscount());
            $this->discountCollectorWriter->save($collectorPluginTransfer);
        }
    }

    /**
     * @param VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return void
     */
    protected function deleteCollectorPlugins(VoucherCodesTransfer $voucherCodesTransfer)
    {
        $voucherCodesTransferArray = $voucherCodesTransfer->toArray();

        if (!is_array($voucherCodesTransferArray[self::COLLECTOR_PLUGINS])) {
            $voucherCodesTransferArray[self::COLLECTOR_PLUGINS] = [];
        }

        $formCollectorPlugins = array_column(
            $voucherCodesTransferArray[self::COLLECTOR_PLUGINS],
            self::ID_DISCOUNT_COLLECTOR)
        ;

        $collectorPluginsCollection = $this->getQueryContainer()
            ->queryDiscountCollectorByDiscountId($voucherCodesTransfer->getIdDiscount())
            ->find()
        ;

        foreach ($collectorPluginsCollection as $collectorPlugin) {
            if (in_array($collectorPlugin->getIdDiscountCollector(), $formCollectorPlugins)) {
                continue;
            }

            $collectorPlugin->delete();
        }
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
        $this->deleteDecisionRules($voucherCodesTransfer);

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
     *
     * @return void
     */
    protected function deleteDecisionRules(VoucherCodesTransfer $voucherCodesTransfer)
    {
        $formDecisionRules = array_column($voucherCodesTransfer->getDecisionRules(), self::ID_DISCOUNT_DECISION_RULE);

        $decisionRulesCollection = $this->getQueryContainer()
            ->queryDecisionRules($voucherCodesTransfer->getIdDiscount())
            ->find()
        ;

        foreach ($decisionRulesCollection as $decisionRule) {
            if (in_array($decisionRule->getIdDiscountDecisionRule(), $formDecisionRules)) {
                continue;
            }

            $decisionRule->delete();
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
