<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerDataChangeRequest;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequestQuery;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestTypeEnum;

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
 * @method \Spryker\Zed\CustomerDataChangeRequest\Business\CustomerDataChangeRequestFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CustomerDataChangeRequestBusinessTester extends Actor
{
    use _generated\CustomerDataChangeRequestBusinessTesterActions;

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
     * @var string
     */
    public const TEST_NON_EMAIL_TYPE = 'non-email-type';

    /**
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer
     */
    public function createTestEmailChangeRequestTransfer(): CustomerDataChangeRequestTransfer
    {
        return (new CustomerDataChangeRequestTransfer())
            ->setIdCustomer(static::TEST_CUSTOMER_ID)
            ->setType(CustomerDataChangeRequestTypeEnum::EMAIL->value)
            ->setVerificationToken(static::TEST_VERIFICATION_TOKEN)
            ->setData(static::TEST_NEW_CUSTOMER_EMAIL);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer
     */
    public function createTestNonEmailChangeRequestTransfer(): CustomerDataChangeRequestTransfer
    {
        return (new CustomerDataChangeRequestTransfer())
            ->setType(static::TEST_NON_EMAIL_TYPE);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createTestCustomerTransfer(): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->setIdCustomer(static::TEST_CUSTOMER_ID)
            ->setEmail(static::TEST_CUSTOMER_EMAIL)
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr');
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

    /**
     * @return void
     */
    public function ensureCustomerDataChangeRequestTableIsEmpty(): void
    {
        $this->getCustomerDataChangeRequestQuery()
            ->deleteAll();
    }

    /**
     * @return \Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequestQuery
     */
    protected function getCustomerDataChangeRequestQuery(): SpyCustomerDataChangeRequestQuery
    {
        return SpyCustomerDataChangeRequestQuery::create();
    }
}
