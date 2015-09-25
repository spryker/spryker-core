<?php

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\CartRuleFormTransfer;
use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountWriter;
use SprykerFeature\Zed\Discount\Communication\Form\CartRuleType;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;

class CartRule implements CartRuleInterface
{

    const CART_RULES_ITERATOR = 'rule_';
    const DATABASE_DATE_FORMAT = 'Y-m-d\TG:i:s\Z';

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
     * @param DiscountQueryContainer $queryContainer
     * @param Store $store
     */
    public function __construct(DiscountQueryContainer $queryContainer, Store $store, DiscountDecisionRuleWriter $discountDecisionRuleWriter, DiscountWriter $discountWriter)
    {
        $this->queryContainer = $queryContainer;
        $this->store = $store;
        $this->discountDecisionRuleWriter = $discountDecisionRuleWriter;
        $this->discountWriter = $discountWriter;
    }

    /**
     * @param CartRuleFormTransfer $cartRuleFormTransfer
     *
     * @return DiscountTransfer
     */
    public function saveCartRule(CartRuleFormTransfer $cartRuleFormTransfer)
    {
        $formData = $cartRuleFormTransfer->toArray();
        $discountTransfer = (new DiscountTransfer())->fromArray($formData, true);
        $discount = $this->saveDiscount($discountTransfer);

        foreach ($formData[CartRuleType::FIELD_DECISION_RULES] as $cartRules) {
            $decisionRuleTransfer = (new DecisionRuleTransfer())->fromArray($cartRules, true);
            $decisionRuleTransfer->setFkDiscount($discount->getIdDiscount());
            $decisionRuleTransfer->setName($discount->getDisplayName());

            $this->discountDecisionRuleWriter->saveDiscountDecisionRule($decisionRuleTransfer);
        }

        return $discountTransfer->fromArray($discount->toArray(), true);
    }

    /**
     * @param int $idDiscount
     *
     * @return array
     */
    public function getCurrentCartRulesDetailsByIdDiscount($idDiscount)
    {
        $discount = $this->queryContainer->queryDiscount()->findOneByIdDiscount($idDiscount);

        $defaultData = $this->fixDateFormats($discount->toArray());

        $rules = $this->queryContainer->queryDecisionRules($idDiscount)->find();

        if ($rules->count() < 1) {
            return $defaultData;
        }

        foreach ($rules as $i => $decisionRule) {
            $defaultData[CartRuleType::FIELD_DECISION_RULES][self::CART_RULES_ITERATOR . (+$i)] = $this->fixDateFormats($decisionRule->toArray());
        }

        return $defaultData;
    }

    /**
     * @param array $entityArray
     *
     * @return array
     */
    protected function fixDateFormats(array $entityArray)
    {
        foreach ($entityArray as $key => $value) {
            if (false === in_array($key, $this->dateTypeFields)) {
                continue;
            }
            if (false === ($value instanceof \DateTime)) {
                $entityArray[$key] = \DateTime::createFromFormat(self::DATABASE_DATE_FORMAT, $value, new \DateTimeZone($this->store->getTimezone()));
            }
        }

        return $entityArray;
    }

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @return SpyDiscount
     */
    protected function saveDiscount($discountTransfer)
    {
        if (null === $discountTransfer->getIdDiscount()) {
            return $this->discountWriter->create($discountTransfer);
        }

        return $this->discountWriter->update($discountTransfer);
    }
}
