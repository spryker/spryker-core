<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessStorage;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CustomerAccessStorage\CustomerAccessStorageFactory getFactory()
 */
class CustomerAccessStorageClient extends AbstractClient implements CustomerAccessStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAuthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        return $this->getFactory()->createCustomerAccessStorageReader()->getAuthenticatedCustomerAccess();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        return $this->getFactory()->createCustomerAccessStorageReader()->getUnauthenticatedCustomerAccess();
    }
}
