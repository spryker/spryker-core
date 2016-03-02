<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;

class VoucherPoolCategory
{

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainer
     */
    protected $discountQueryContainer;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainer $discountQueryContainer
     */
    public function __construct(DiscountQueryContainer $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @return array
     */
    public function getAvailableVoucherPoolCategories()
    {
        $categories = $this->discountQueryContainer
            ->queryDiscountVoucherPoolCategory()
            ->orderByName()
            ->find();

        $availableVoucherPoolCategories = [];

        foreach ($categories as $category) {
            $availableVoucherPoolCategories[$category->getIdDiscountVoucherPoolCategory()] = $category->getName();
        }

        return $availableVoucherPoolCategories;
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]
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
