<?php

namespace Spryker\Zed\Discount\Business\Model;

use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\CartRuleTransfer;
use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Discount\Business\Writer\DiscountCollectorWriter;
use Spryker\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter;
use Spryker\Zed\Discount\Business\Writer\DiscountWriter;
use Spryker\Zed\Discount\Communication\Form\CartRuleForm;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Orm\Zed\Discount\Persistence\SpyDiscount;

class CartRule implements CartRuleInterface
{

    const CART_RULES_ITERATOR = 'rule_';
    const DATABASE_DATE_FORMAT = 'Y-m-d\TG:i:s\Z';
    const COLLECTOR_ITERATOR = 'collector_';

    /**
     * @var DiscountQueryContainer
     */
    protected $queryContainer;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @var DiscountDecisionRuleWriter
     */
    protected $discountDecisionRuleWriter;

    /**
     * @var DiscountWriter
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
     * @var DiscountCollectorWriter
     */
    protected $discountCollectorWriter;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainer $queryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter $discountDecisionRuleWriter
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountWriter $discountWriter
     * @param \Spryker\Zed\Discount\Business\Writer\DiscountCollectorWriter $discountCollectorWriter
     */
    public function __construct(
        DiscountQueryContainer $queryContainer,
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

        foreach ($formData[CartRuleForm::FIELD_DECISION_RULES] as $cartRules) {
            $decisionRuleTransfer = (new DecisionRuleTransfer())->fromArray($cartRules, true);
            $decisionRuleTransfer->setFkDiscount($discountEntity->getIdDiscount());
            $decisionRuleTransfer->setName($discountEntity->getDisplayName());

            $this->discountDecisionRuleWriter->saveDiscountDecisionRule($decisionRuleTransfer);
        }

        foreach ($cartRuleFormTransfer->getCollectorPlugins() as $collectorTransfer) {
            $collectorTransfer->setFkDiscount($discountEntity->getIdDiscount());
            $this->discountCollectorWriter->save($collectorTransfer);
        }

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
            $discount[CartRuleForm::FIELD_DECISION_RULES][self::CART_RULES_ITERATOR . (+$key)] =
                $this->updateDateTimeZoneToStoreDefault($decisionRuleEntity->toArray());
        }

        foreach ($discountEntity->getDiscountCollectors() as $key => $collectorEntity) {
            $discount[CartRuleForm::FIELD_COLLECTOR_PLUGINS][self::COLLECTOR_ITERATOR . (+$key)] =
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

}
