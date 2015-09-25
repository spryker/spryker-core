<?php

namespace SprykerFeature\Zed\Discount\Business\Model;

use SprykerFeature\Zed\Discount\Business\Model\CartRuleInterface;
use SprykerEngine\Shared\Kernel\Store;
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
    public function __construct(DiscountQueryContainer $queryContainer, Store $store)
    {
        $this->queryContainer = $queryContainer;
        $this->store = $store;
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

        if ($rules->count() > 0) {
            foreach ($rules as $i => $decisionRule) {
                $defaultData[CartRuleType::FIELD_CART_RULES][self::CART_RULES_ITERATOR . (+$i)] = $this->fixDateFormats($decisionRule->toArray());
            }
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
}
