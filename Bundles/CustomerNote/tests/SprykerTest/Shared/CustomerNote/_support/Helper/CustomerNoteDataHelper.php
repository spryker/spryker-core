<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\CustomerNote\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Orm\Zed\CustomerNote\Persistence\SpyCustomerNote;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelper;
use SprykerTest\Shared\User\Helper\UserDataHelper;

class CustomerNoteDataHelper extends Module
{
    const TEST_NOTE_MESSAGE = 'test';

    /**
     * @param int $fkCustomer
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
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

    /**
     * @param int $fkCustomer
     * @param int $number
     *
     * @return void
     */
    public function hydrateCustomerNotesTableForCustomer(int $fkCustomer, int $number)
    {
        for ($i = 0; $i < $number; $i++) {
            $noteTransfer = $this->getCustomerNoteTransfer($fkCustomer);
            $noteEntity = new SpyCustomerNote();
            $noteEntity->fromArray($noteTransfer->toArray());
            $noteEntity->save();
        }
    }
}
