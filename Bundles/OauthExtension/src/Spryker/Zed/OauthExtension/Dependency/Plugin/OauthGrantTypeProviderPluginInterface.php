<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use League\OAuth2\Server\Grant\AbstractGrant;

interface OauthGrantTypeProviderPluginInterface
{
    /**
     * Specification:
     *  - Returns grant type name.
     *
     * @api
     *
     * @return string
     */
    public function getGrantTypeName(): string;

    /**
     * Specification:
     *  - Returns Grant type object instance.
     *
     * @api
     *
     * @return \League\OAuth2\Server\Grant\AbstractGrant
     */
    public function getGrantType(): AbstractGrant;
}
