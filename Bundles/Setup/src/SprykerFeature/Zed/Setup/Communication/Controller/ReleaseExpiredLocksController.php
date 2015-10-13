<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Library\Setup;

class ReleaseExpiredLocksController extends AbstractController
{

    public function indexAction()
    {
        $releasedLocksCount = $this->facadeMisc->releaseExpiredLocks();
        Setup::renderAndExit('Released ' . $releasedLocksCount . ' expired locks.');
    }

}
