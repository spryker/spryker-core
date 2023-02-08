<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CustomerStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\InvalidatedCustomerConditionsTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer;
use Spryker\Client\CustomerStorage\CustomerStorageClientInterface;

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
 * @method \Spryker\Client\CustomerStorage\CustomerStorageClientInterface getClient()
 *
 * @SuppressWarnings(PHPMD)
 */
class CustomerStorageClientTester extends Actor
{
    use _generated\CustomerStorageClientTesterActions;

    /**
     * @var string
     */
    protected const CUSTOMER_REFERENCE_1 = 'TEST--1';

    /**
     * @var string
     */
    protected const CUSTOMER_REFERENCE_2 = 'TEST--2';

    /**
     * @return \Spryker\Client\CustomerStorage\CustomerStorageClientInterface
     */
    public function getCustomerStorageClient(): CustomerStorageClientInterface
    {
        return $this->getLocator()
            ->customerStorage()
            ->client();
    }

    /**
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer
     */
    public function createInvalidatedCustomerCriteriaTransfer(): InvalidatedCustomerCriteriaTransfer
    {
        $invalidatedCustomerConditionsTransfer = (new InvalidatedCustomerConditionsTransfer())
            ->addCustomerReference(static::CUSTOMER_REFERENCE_1)
            ->addCustomerReference(static::CUSTOMER_REFERENCE_2);

        return (new InvalidatedCustomerCriteriaTransfer())
            ->setInvalidatedCustomerConditions($invalidatedCustomerConditionsTransfer);
    }
}
