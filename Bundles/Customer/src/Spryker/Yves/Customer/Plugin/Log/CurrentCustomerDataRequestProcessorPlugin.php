<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Customer\Plugin\Log;

use Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Customer\CustomerFactory getFactory()
 * @method \Spryker\Yves\Customer\CustomerConfig getConfig()
 */
class CurrentCustomerDataRequestProcessorPlugin extends AbstractPlugin implements LogProcessorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds customer email and customer reference from the current request.
     *
     * @api
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $data): array
    {
        return $this->getFactory()->createCurrentCustomerDataRequestLogProcessor()->__invoke($data);
    }
}
