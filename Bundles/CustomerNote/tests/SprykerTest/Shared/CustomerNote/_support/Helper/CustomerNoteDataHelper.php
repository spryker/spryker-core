<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\CustomerNote\Helper;

use Codeception\Module;
use Codeception\Util\Stub;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerNotesCollectionTransfer;
use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Orm\Zed\CustomerNote\Persistence\SpyCustomerNote;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailBridge;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelper;
use SprykerTest\Shared\User\Helper\UserDataHelper;

class CustomerNoteDataHelper extends Module
{
    const TEST_NOTE_MESSAGE = 'test';

    /**
     * @return SpyCustomerNoteEntityTransfer
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function getCustomerNoteTransfer(int $fkCustomer = 0): SpyCustomerNoteEntityTransfer
    {
        if (!$fkCustomer) {
            $fkCustomer = $this->getModule('\\' . CustomerDataHelper::class)->haveCustomer()->getIdCustomer();
        }

        $userTransfer = $this->getModule('\\' . UserDataHelper::class)->haveUser();
        $noteTransfer = new SpyCustomerNoteEntityTransfer();
        $noteTransfer->setMessage(static::TEST_NOTE_MESSAGE);
        $noteTransfer->setUsername(
            $userTransfer->getFirstName()
        );
        $noteTransfer->setFkCustomer($fkCustomer);
        $noteTransfer->setFkUser($userTransfer->getIdUser());

        return $noteTransfer;
    }

    public function hydrateCustomerNotes(int $fkCustomer, int $number)
    {
        $customerNotesCollectionTransfer = new CustomerNotesCollectionTransfer();
        for($i = 0; $i < $number; $i++) {
            $noteTransfer = $this->getCustomerNoteTransfer($fkCustomer);
            $noteEntity = new SpyCustomerNote();
            $noteEntity->fromArray($noteTransfer->toArray());
            $noteEntity->save();
        }

    }
}
