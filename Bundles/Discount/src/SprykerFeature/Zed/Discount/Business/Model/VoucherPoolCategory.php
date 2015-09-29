<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\OrderInterface;
use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Distributor\DistributorInterface;
use SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule\AbstractDecisionRule;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule;

class VoucherPoolCategory
{

    /**
     * @var DiscountQueryContainer
     */
    protected $discountQueryContainer;

    /**
     * @param DiscountQueryContainer $discountQueryContainer
     */
    public function __construct(DiscountQueryContainer $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    public function getAvailableVoucherPoolCategories()
    {
        $categories = $this->discountQueryContainer
            ->queryDiscountVoucherPoolCategory()
            ->orderByName()
            ->find()
        ;

        $availableVoucherPoolCategories = [];

        foreach ($categories as $category) {
            $availableVoucherPoolCategories[$category->getIdDiscountVoucherPoolCategory()] = $category->getName();
        }

        return $availableVoucherPoolCategories;
    }


    /**
     * @return SpyDiscount[]
     */
    public function retrieveActiveAndRunningDiscounts()
    {
        return $this->queryContainer->queryActiveAndRunningDiscounts()->find();
    }

    /**
     * @return array
     */
    protected function retrieveDiscountsToBeCalculated()
    {
        $discounts = $this->retrieveActiveAndRunningDiscounts();
        $discountsToBeCalculated = [];
        $errors = [];

        foreach ($discounts as $discount) {
            $discountTransfer = new DiscountTransfer();
            $discountTransfer->fromArray($discount->toArray(), true);
            $result = $this->decisionRule->evaluate(
                $discountTransfer,
                $this->discountContainer,
                $this->getDecisionRulePlugins($discount->getPrimaryKey())
            );

            if ($result->isSuccess()) {
                $discountsToBeCalculated[] = $discountTransfer;
            } else {
                $errors = array_merge($errors, $result->getErrors());
            }
        }

        return [
            self::KEY_DISCOUNTS => $discountsToBeCalculated,
            self::KEY_ERRORS => $errors,
        ];
    }

}
