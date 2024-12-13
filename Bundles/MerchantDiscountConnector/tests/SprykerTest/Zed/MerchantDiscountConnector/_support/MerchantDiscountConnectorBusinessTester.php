<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantDiscountConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\ClauseTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\MerchantDiscountConnector\Business\MerchantDiscountConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantDiscountConnectorBusinessTester extends Actor
{
    use _generated\MerchantDiscountConnectorBusinessTesterActions;

    /**
     * @see \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_LIST
     *
     * @var string
     */
    protected const TYPE_LIST = 'list';

    /**
     * @param string $operator
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    public function createClauseTransfer(string $operator, string $merchantReference): ClauseTransfer
    {
        return (new ClauseTransfer())
            ->setOperator($operator)
            ->setValue($merchantReference)
            ->setAcceptedTypes([static::TYPE_LIST]);
    }
}
