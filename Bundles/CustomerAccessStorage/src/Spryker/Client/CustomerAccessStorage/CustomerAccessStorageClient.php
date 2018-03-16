<?php

namespace Spryker\Client\CustomerAccessStorage;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Client\Kernel\AbstractClient;

/***
 * @method \Spryker\Client\CustomerAccessStorage\CustomerAccessStorageFactory getFactory
 */
class CustomerAccessStorageClient extends AbstractClient implements CustomerAccessStorageClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAuthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        return $this->getFactory()->createCustomerAccessStorageReader()->getAuthenticatedCustomerAccess();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        return $this->getFactory()->createCustomerAccessStorageReader()->getUnauthenticatedCustomerAccess();
    }
}
