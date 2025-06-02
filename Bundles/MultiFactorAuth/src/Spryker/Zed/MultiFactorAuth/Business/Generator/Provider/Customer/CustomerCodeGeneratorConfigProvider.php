<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\Customer;

use Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\CodeGeneratorConfigProviderInterface;
use Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig;

class CustomerCodeGeneratorConfigProvider implements CodeGeneratorConfigProviderInterface
{
    /**
     * @param \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig $config
     */
    public function __construct(protected MultiFactorAuthConfig $config)
    {
    }

    /**
     * @return int
     */
    public function getCodeLength(): int
    {
        return $this->config->getCustomerCodeLength();
    }

    /**
     * @return int
     */
    public function getCodeValidityTtl(): int
    {
        return $this->config->getCustomerCodeValidityTtl();
    }
}
