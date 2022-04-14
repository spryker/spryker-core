<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppCatalogGui\Business;

use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AppCatalogGui\Business\AppCatalogGuiBusinessFactory getFactory()
 */
class AppCatalogGuiFacade extends AbstractFacade implements AppCatalogGuiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function requestAccessToken(): AccessTokenResponseTransfer
    {
        return $this->getFactory()->createAccessTokenReader()->requestAccessToken();
    }
}
