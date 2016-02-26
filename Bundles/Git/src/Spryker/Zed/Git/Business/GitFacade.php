<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Git\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Git\Business\GitBusinessFactory getFactory()
 */
class GitFacade extends AbstractFacade implements GitFacadeInterface
{

    /**
     * @api
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getFactory()->getConsoleCommands();
    }

}
