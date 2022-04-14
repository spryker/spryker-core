<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AppCatalogGui;

use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\AppCatalogGui\AppCatalogGuiFactory getFactory()
 */
class AppCatalogGuiClient extends AbstractClient implements AppCatalogGuiClientInterface
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
        return $this->getFactory()
            ->createAccessTokenClient()
            ->requestAccessToken();
    }
}
