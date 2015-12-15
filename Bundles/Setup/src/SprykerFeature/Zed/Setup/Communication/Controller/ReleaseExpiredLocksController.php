<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
