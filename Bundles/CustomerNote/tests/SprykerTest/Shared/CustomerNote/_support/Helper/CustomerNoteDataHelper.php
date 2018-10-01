<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\CustomerNote\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CustomerNoteDataHelper extends Module
{
    use LocatorHelperTrait;

    public const TEST_NOTE_AUTHOR = 'test_author';
    public const TEST_NOTE_MESSAGE = 'test_message';

    /**
     * @return \Spryker\Zed\CustomerNote\Business\CustomerNoteFacadeInterface
     */
    protected function getCustomerNoteFacade()
    {
        return $this->getLocator()->customerNote()->facade();
    }

    /**
     * @param int $fkUser
     * @param int $fkCustomer
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    public function getCustomerNoteTransfer(int $fkUser, int $fkCustomer): SpyCustomerNoteEntityTransfer
    {
        $noteTransfer = new SpyCustomerNoteEntityTransfer();
        $noteTransfer->setMessage(static::TEST_NOTE_MESSAGE);
        $noteTransfer->setUsername(static::TEST_NOTE_AUTHOR);
        $noteTransfer->setFkCustomer($fkCustomer);
        $noteTransfer->setFkUser($fkUser);

        return $noteTransfer;
    }

    /**
     * @param int $fkUser
     * @param int $fkCustomer
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    public function haveCustomerNote(int $fkUser, int $fkCustomer)
    {
        return $this->getCustomerNoteFacade()->addNote(
            $this->getCustomerNoteTransfer($fkUser, $fkCustomer)
        );
    }
}
