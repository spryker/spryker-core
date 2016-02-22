<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
