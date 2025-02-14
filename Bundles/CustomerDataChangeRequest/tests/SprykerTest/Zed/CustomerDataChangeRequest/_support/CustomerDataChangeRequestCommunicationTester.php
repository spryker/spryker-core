<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\CustomerDataChangeRequest;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerTransfer;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class CustomerDataChangeRequestCommunicationTester extends Actor
{
    use _generated\CustomerDataChangeRequestCommunicationTesterActions;

    /**
     * @var string
     */
    public const TEST_CUSTOMER_EMAIL = 'test@spryker.com';

    /**
     * @var string
     */
    public const TEST_NEW_CUSTOMER_EMAIL = 'new.test@spryker.com';

    /**
     * @var string
     */
    public const TEST_VERIFICATION_TOKEN = 'test-token';

    /**
     * @var int
     */
    public const TEST_CUSTOMER_ID = 1;

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createTestCustomerTransfer(): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->setIdCustomer(static::TEST_CUSTOMER_ID)
            ->setEmail(static::TEST_CUSTOMER_EMAIL);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createTestCustomerTransferWithNewEmail(): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->setIdCustomer(static::TEST_CUSTOMER_ID)
            ->setEmail(static::TEST_NEW_CUSTOMER_EMAIL);
    }
}
