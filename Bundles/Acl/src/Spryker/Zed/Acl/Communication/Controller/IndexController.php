<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @return void
     */
    public function indexAction()
    {
    }

    /**
     * @return void
     */
    public function deniedAction()
    {
    }
}
