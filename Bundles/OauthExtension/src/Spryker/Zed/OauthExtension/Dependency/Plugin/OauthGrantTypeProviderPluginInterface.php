<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface;

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
     *  -
     *
     * @api
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface
     */
    public function getGrantType(): GrantInterface;
}
