<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\GuestCartCustomerReferenceGenerator;

use Spryker\Client\PersistentCart\PersistentCartConfig;

class GuestCartCustomerReferenceGenerator implements GuestCartCustomerReferenceGeneratorInterface
{
    /**
     * @var \Spryker\Client\PersistentCart\PersistentCartConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\PersistentCart\PersistentCartConfig $config
     */
    public function __construct(PersistentCartConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $customerReference
     *
     * @return string
     */
    public function generateGuestCartCustomerReference(string $customerReference): string
    {
        return $this->config->getPersistentCartAnonymousPrefix() . $customerReference;
    }
}
