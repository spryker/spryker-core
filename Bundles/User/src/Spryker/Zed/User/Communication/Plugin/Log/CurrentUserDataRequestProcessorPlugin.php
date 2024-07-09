<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Plugin\Log;

use Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\User\Communication\UserCommunicationFactory getFactory()
 * @method \Spryker\Zed\User\UserConfig getConfig()
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade()
 */
class CurrentUserDataRequestProcessorPlugin extends AbstractPlugin implements LogProcessorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds username and user UUID from the current request.
     *
     * @api
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $data): array
    {
        return $this->getFactory()->createCurrentUserDataRequestLogProcessor()->__invoke($data);
    }
}
