<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Business;

use Spryker\Zed\CustomersRestApi\Business\Addresses\AddressesUuidWriter;
use Spryker\Zed\CustomersRestApi\Business\Addresses\AddressesUuidWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomersRestApi\CustomersRestApiConfig getConfig()
 */
class CustomersRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomersRestApi\Business\Addresses\AddressesUuidWriterInterface
     */
    public function createCustomersAddressesUuidUpdater(): AddressesUuidWriterInterface
    {
        return new AddressesUuidWriter(
            $this->getEntityManager()
        );
    }
}
