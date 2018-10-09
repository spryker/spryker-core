<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\GuestCartCustomerReferenceGenerator;

class GuestCartCustomerReferenceGenerator implements GuestCartCustomerReferenceGeneratorInterface
{
    /**
     * @var string
     */
    protected $persistentCartAnonymousPrefix;

    /**
     * @param string $persistentCartAnonymousPrefix
     */
    public function __construct(string $persistentCartAnonymousPrefix)
    {
        $this->persistentCartAnonymousPrefix = $persistentCartAnonymousPrefix;
    }

    /**
     * @param string $customerReference
     *
     * @return string
     */
    public function generateGuestCartCustomerReference(string $customerReference): string
    {
        return $this->persistentCartAnonymousPrefix . $customerReference;
    }
}
