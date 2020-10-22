<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Finisher;

use Generated\Shared\Transfer\CustomerTransfer;

class ImpersonationFinisher implements ImpersonationFinisherInterface
{
    /**
     * @var \Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationFinisherPluginInterface[]
     */
    protected $impersonationFinisherPlugins;

    /**
     * @param \Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationFinisherPluginInterface[] $impersonationFinisherPlugins
     */
    public function __construct(array $impersonationFinisherPlugins)
    {
        $this->impersonationFinisherPlugins = $impersonationFinisherPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function finishImpersonation(CustomerTransfer $customerTransfer): void
    {
        $this->executeImpersonationFinisherPlugins($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function executeImpersonationFinisherPlugins(CustomerTransfer $customerTransfer): void
    {
        foreach ($this->impersonationFinisherPlugins as $impersonationFinisherPlugin) {
            $impersonationFinisherPlugin->finish($customerTransfer);
        }
    }
}
