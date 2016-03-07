<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Library\Setup;

class ReleaseExpiredLocksController extends AbstractController
{

    /**
     * @return void
     */
    public function indexAction()
    {
        $releasedLocksCount = $this->facadeMisc->releaseExpiredLocks();
        Setup::renderAndExit('Released ' . $releasedLocksCount . ' expired locks.');
    }

}
