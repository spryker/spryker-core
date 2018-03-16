<?php

namespace Spryker\Client\CustomerAccessStorage\Storage;

use Generated\Shared\Transfer\CustomerAccessTransfer;

interface CustomerAccessStorageReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer;

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAuthenticatedCustomerAccess(): CustomerAccessTransfer;
}