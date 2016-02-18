<?php

namespace Spryker\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherCodesTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class VoucherCodesWriter extends AbstractWriter
{

    const COLLECTOR_PLUGINS = 'collector_plugins';
    const ID_DISCOUNT_COLLECTOR = 'id_discount_collector';
    const ID_DISCOUNT_DECISION_RULE = 'id_discount_decision_rule';

    /**
     * @var \Spryker\Zed\Discount\Business\Writer\DiscountWriter
     */
    protected $discountWriter;

    /**
     * @var \Spryker\Zed\Discount\Business\Writer\DiscountVoucherPoolWriter
     */
    protected $discountVoucherPoolWriter;

    /**
     * @var \Spryker\Zed\Discount\Business\Writer\DiscountVoucherPoolCategoryWriter
     */
    protected $discountVoucherPoolCategoryWriter;

    /**
     * @var \Spryker\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter
     */
    protected $discountDecisionRuleWriter;

    /**
     * @var \Spryker\Zed\Discount\Business\Writer\DiscountCollectorWriter
     */
    private $discountCollectorWriter;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountWriter $discountWriter
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountVoucherPoolWriter $discountVoucherPoolWriter
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountVoucherPoolCategoryWriter $discountVoucherPoolCategoryWriter
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter $discountDecisionRuleWriter
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountCollectorWriter $discountCollectorWriter
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
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherPoolTransfer
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
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
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
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
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
            self::ID_DISCOUNT_COLLECTOR
        );

        $collectorPluginsCollection = $this->getQueryContainer()
            ->queryDiscountCollectorByDiscountId($voucherCodesTransfer->getIdDiscount())
            ->find();

        foreach ($collectorPluginsCollection as $collectorPlugin) {
            if (in_array($collectorPlugin->getIdDiscountCollector(), $formCollectorPlugins)) {
                continue;
            }

            $collectorPlugin->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
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
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return void
     */
    protected function deleteDecisionRules(VoucherCodesTransfer $voucherCodesTransfer)
    {
        $formDecisionRules = array_column($voucherCodesTransfer->getDecisionRules(), self::ID_DISCOUNT_DECISION_RULE);

        $decisionRulesCollection = $this->getQueryContainer()
            ->queryDecisionRules($voucherCodesTransfer->getIdDiscount())
            ->find();

        foreach ($decisionRulesCollection as $decisionRule) {
            if (in_array($decisionRule->getIdDiscountDecisionRule(), $formDecisionRules)) {
                continue;
            }

            $decisionRule->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory $voucherPoolCategory
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    protected function saveDiscountVoucherPool(VoucherCodesTransfer $voucherCodesTransfer, $voucherPoolCategory)
    {
        $voucherPoolTransfer = (new VoucherPoolTransfer())->fromArray($voucherCodesTransfer->toArray(), true);
        $voucherPoolTransfer->setFkDiscountVoucherPoolCategory($voucherPoolCategory->getIdDiscountVoucherPoolCategory());
        $voucherPool = $this->discountVoucherPoolWriter->save($voucherPoolTransfer);

        return $voucherPool;
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool $voucherPool
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
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
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function createDiscountTransfer(VoucherCodesTransfer $voucherCodesTransfer)
    {
        return (new DiscountTransfer())->fromArray($voucherCodesTransfer->toArray(), true);
    }

}
