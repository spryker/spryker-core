<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class VersionPageController extends AbstractController
{

    /**
     * @return void
     */
    public function publishAction()
    {
        $cmsPageTransfer = $this->getFactory()->getCmsFacade()->publishAndVersion(1);
//        $cmsPageTransfer = $this->getFactory()->getCmsFacade()->publishAndVersion(2);
        dump($cmsPageTransfer);
        dump('Published');die;
    }

    /**
     * @return void
     */
    public function rollbackAction()
    {

        $this->getFactory()->getCmsFacade()->rollback(1,6);
        dump('Revert');die;
    }
}
