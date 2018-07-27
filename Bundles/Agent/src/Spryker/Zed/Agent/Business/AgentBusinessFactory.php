<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business;

use Spryker\Zed\Agent\Business\AgentFinder\AgentFinder;
use Spryker\Zed\Agent\Business\AgentFinder\AgentFinderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface getRepository()
 */
class AgentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Agent\Business\AgentFinder\AgentFinderInterface
     */
    public function createAgentFinder(): AgentFinderInterface
    {
        return new AgentFinder(
            $this->getRepository()
        );
    }
}
