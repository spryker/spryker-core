<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Agent\Business\AgentBusinessFactory getFactory()
 */
class AgentFacade extends AbstractFacade implements AgentFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getAgentByUsername(string $username): UserTransfer
    {
        return $this->getFactory()
            ->createAgentFinder()
            ->getAgentByUsername($username);
    }
}
