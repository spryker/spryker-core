<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\CartRuleTransfer;
use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Discount\Business\Writer\DiscountCollectorWriter;
use Spryker\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter;
use Spryker\Zed\Discount\Business\Writer\DiscountWriter;
use Spryker\Zed\Discount\Communication\Form\CartRuleForm;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class CartRule implements CartRuleInterface
{

    const CART_RULES_ITERATOR = 'rule_';
    const DATABASE_DATE_FORMAT = 'Y-m-d\TG:i:s\Z';
    const COLLECTOR_ITERATOR = 'collector_';
    const ID_DISCOUNT_COLLECTOR = 'id_discount_collector';
    const ID_DISCOUNT_DECISION_RULE = 'id_discount_decision_rule';

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter
     */
    protected $discountDecisionRuleWriter;

    /**
     * @var \Spryker\Zed\Discount\Business\Writer\DiscountWriter
     */
    protected $discountWriter;

    /**
     * @var array
     */
    protected $dateTypeFields = [
        'valid_from',
        'valid_to',
        'created_at',
        'updated_at',
    ];

    /**
     * @var \Spryker\Zed\Discount\Business\Writer\DiscountCollectorWriter
     */
    protected $discountCollectorWriter;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $queryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter $discountDecisionRuleWriter
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountWriter $discountWriter
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountCollectorWriter $discountCollectorWriter
     */
    public function __construct(
        DiscountQueryContainerInterface $queryContainer,
        Store $store,
        DiscountDecisionRuleWriter $discountDecisionRuleWriter,
        DiscountWriter $discountWriter,
        DiscountCollectorWriter $discountCollectorWriter
    ) {
        $this->queryContainer = $queryContainer;
        $this->store = $store;
        $this->discountDecisionRuleWriter = $discountDecisionRuleWriter;
        $this->discountWriter = $discountWriter;
        $this->discountCollectorWriter = $discountCollectorWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\CartRuleTransfer $cartRuleFormTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function saveCartRule(CartRuleTransfer $cartRuleFormTransfer)
    {
        $formData = $cartRuleFormTransfer->toArray();
        $discountTransfer = (new DiscountTransfer())->fromArray($formData, true);
        $discountEntity = $this->saveDiscount($discountTransfer);

        $this->saveDecisionRules($cartRuleFormTransfer, $discountEntity);
        $this->saveCollectorPlugins($cartRuleFormTransfer, $discountEntity);

        return $discountTransfer->fromArray($discountEntity->toArray(), true);
    }

    /**
     * @param int $idDiscount
     *
     * @return array
     */
    public function getCurrentCartRulesDetailsByIdDiscount($idDiscount)
    {
        $discountEntity = $this->queryContainer->queryDiscount()->findOneByIdDiscount($idDiscount);
        $discount = $this->updateDateTimeZoneToStoreDefault($discountEntity->toArray());

        foreach ($discountEntity->getDecisionRules() as $key => $decisionRuleEntity) {
            $discount[CartRuleForm::FIELD_DECISION_RULES][] =
                $this->updateDateTimeZoneToStoreDefault($decisionRuleEntity->toArray());
        }

        foreach ($discountEntity->getDiscountCollectors() as $key => $collectorEntity) {
            $discount[CartRuleForm::FIELD_COLLECTOR_PLUGINS][] =
                $this->updateDateTimeZoneToStoreDefault($collectorEntity->toArray());
        }

        return $discount;
    }

    /**
     * @param array $discount
     *
     * @return array
     */
    protected function updateDateTimeZoneToStoreDefault(array $discount)
    {
        foreach ($discount as $field => $value) {
            if (in_array($field, $this->dateTypeFields) === false) {
                continue;
            }

            if (($value instanceof DateTime) === false) {
                $discount[$field] = DateTime::createFromFormat(
                    self::DATABASE_DATE_FORMAT,
                    $value,
                    new DateTimeZone($this->store->getTimezone())
                );
            }
        }

        return $discount;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    protected function saveDiscount($discountTransfer)
    {
        if ($discountTransfer->getIdDiscount() === null) {
            return $this->discountWriter->create($discountTransfer);
        }

        return $this->discountWriter->update($discountTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartRuleTransfer $cartRuleFormTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return void
     */
    protected function saveDecisionRules(CartRuleTransfer $cartRuleFormTransfer, SpyDiscount $discountEntity)
    {
        $this->deleteDecisionRules($cartRuleFormTransfer, $discountEntity);

        foreach ($cartRuleFormTransfer->getDecisionRules() as $cartRules) {
            $decisionRuleTransfer = (new DecisionRuleTransfer())->fromArray($cartRules, true);
            $decisionRuleTransfer->setFkDiscount($discountEntity->getIdDiscount());
            $decisionRuleTransfer->setName($discountEntity->getDisplayName());

            $this->discountDecisionRuleWriter->saveDiscountDecisionRule($decisionRuleTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CartRuleTransfer $cartRuleFormTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    protected function deleteDecisionRules(CartRuleTransfer $cartRuleFormTransfer, SpyDiscount $discountEntity)
    {
        $formDecisionRules = array_column(
            $cartRuleFormTransfer->getDecisionRules(),
            self::ID_DISCOUNT_DECISION_RULE
        );

        $decisionRulesCollection = $this->queryContainer
            ->queryDecisionRules($discountEntity->getIdDiscount())
            ->find();

        foreach ($decisionRulesCollection as $decisionRule) {
            if (in_array($decisionRule->getIdDiscountDecisionRule(), $formDecisionRules)) {
                continue;
            }

            $decisionRule->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CartRuleTransfer $cartRuleFormTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return void
     */
    protected function saveCollectorPlugins(CartRuleTransfer $cartRuleFormTransfer, SpyDiscount $discountEntity)
    {
        $this->deleteCollectorPlugins($cartRuleFormTransfer, $discountEntity);
        foreach ($cartRuleFormTransfer->getCollectorPlugins() as $collectorTransfer) {
            $collectorTransfer->setFkDiscount($discountEntity->getIdDiscount());
            $this->discountCollectorWriter->save($collectorTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CartRuleTransfer $cartRuleFormTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return void
     */
    protected function deleteCollectorPlugins(CartRuleTransfer $cartRuleFormTransfer, SpyDiscount $discountEntity)
    {
        $formCollectorPlugins = [];
        foreach ($cartRuleFormTransfer->getCollectorPlugins() as $collectorPlugin) {
            $formCollectorPlugins[] = $collectorPlugin->getIdDiscountCollector();
        }

        $collectorPluginsCollection = $this->queryContainer
            ->queryDiscountCollectorByDiscountId($discountEntity->getIdDiscount())
            ->find();

        foreach ($collectorPluginsCollection as $collectorPlugin) {
            if (in_array($collectorPlugin->getIdDiscountCollector(), $formCollectorPlugins)) {
                continue;
            }

            $collectorPlugin->delete();
        }
    }

}
