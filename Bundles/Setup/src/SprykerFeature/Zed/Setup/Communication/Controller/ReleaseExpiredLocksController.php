<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class ReleaseExpiredLocksController extends AbstractController
{

    public function indexAction()
    {
        $releasedLocksCount = $this->facadeMisc->releaseExpiredLocks();
        \SprykerFeature_Zed_Library_Setup::renderAndExit('Released ' . $releasedLocksCount . ' expired locks.');
    }

}
