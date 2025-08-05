<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController as SprykerShopAbstractController;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class AbstractController extends SprykerShopAbstractController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->getFactory()->createCompanyUserReader()->getCurrentCompanyUser();
    }
}
