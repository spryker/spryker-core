<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Communication\Controller;

use Spryker\Shared\Library\Application\Version;
use Spryker\Zed\Application\Communication\Controller\AbstractController;

class RevController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $revisionInfo = Version::getRevTxt();

        return $this->viewResponse(['revisionInfo' => $revisionInfo]);
    }

}
