<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business;

use Spryker\Zed\Agent\Business\Agent\AgentReader;
use Spryker\Zed\Agent\Business\Agent\AgentReaderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface getRepository()
 * @method \Spryker\Zed\Agent\AgentConfig getConfig()
 */
class AgentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Agent\Business\Agent\AgentReaderInterface
     */
    public function createAgentReader(): AgentReaderInterface
    {
        return new AgentReader($this->getRepository());
    }
}
