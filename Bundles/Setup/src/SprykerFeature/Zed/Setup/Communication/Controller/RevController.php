<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication\Controller;

use SprykerFeature\Shared\Library\Application\Version;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

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
