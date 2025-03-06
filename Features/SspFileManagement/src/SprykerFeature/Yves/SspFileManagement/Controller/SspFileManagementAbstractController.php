<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerFeature\Yves\SspFileManagement\SspFileManagementFactory getFactory()
 */
class SspFileManagementAbstractController extends AbstractController
{
 /**
  * @return void
  */
    public function initialize(): void
    {
        parent::initialize();
        $this->getFactory()->createCompanyUserReader()->getCurrentCompanyUser();
    }
}
