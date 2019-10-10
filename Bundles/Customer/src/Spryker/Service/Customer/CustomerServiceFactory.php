<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Customer;

use Spryker\Service\Customer\Address\CustomerAddressKeyGenerator;
use Spryker\Service\Customer\Address\CustomerAddressKeyGeneratorInterface;
use Spryker\Service\Customer\Address\CustomerAddressSanitizer;
use Spryker\Service\Customer\Address\CustomerAddressSanitizerInterface;
use Spryker\Service\Customer\Dependency\Service\CustomerToUtilEncodingServiceInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\Customer\CustomerConfig getConfig()
 */
class CustomerServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Customer\Address\CustomerAddressKeyGeneratorInterface
     */
    public function createCustomerAddressKeyGenerator(): CustomerAddressKeyGeneratorInterface
    {
        return new CustomerAddressKeyGenerator(
            $this->getConfig(),
            $this->getCustomerToUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Service\Customer\Dependency\Service\CustomerToUtilEncodingServiceInterface
     */
    public function getCustomerToUtilEncodingService(): CustomerToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Service\Customer\Address\CustomerAddressSanitizerInterface
     */
    public function createCustomerAddressSanitizer(): CustomerAddressSanitizerInterface
    {
        return new CustomerAddressSanitizer($this->getConfig());
    }
}
