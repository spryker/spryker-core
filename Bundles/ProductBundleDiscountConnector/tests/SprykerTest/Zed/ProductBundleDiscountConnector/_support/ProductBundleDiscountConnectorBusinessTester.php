<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundleDiscountConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\ClauseTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\ProductBundleDiscountConnector\Business\ProductBundleDiscountConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductBundleDiscountConnectorBusinessTester extends Actor
{
    use _generated\ProductBundleDiscountConnectorBusinessTesterActions;

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_STRING
     *
     * @var string
     */
    protected const TYPE_STRING = 'string';

    /**
     * @param string $attributeKey
     * @param string $attributeValue
     * @param string $operator
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    public function createClauseTransfer(string $attributeKey, string $attributeValue, string $operator = '='): ClauseTransfer
    {
        return (new ClauseTransfer())->setOperator($operator)
            ->setAttribute($attributeKey)
            ->setValue($attributeValue)
            ->setAcceptedTypes([
                static::TYPE_STRING,
            ]);
    }
}
