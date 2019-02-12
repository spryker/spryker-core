<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCompanyUserConnector;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\OauthCompanyUserConnector\OauthCompanyUserConnectorFactory getFactory()
 */
class OauthCompanyUserConnectorClient extends AbstractClient implements OauthCompanyUserConnectorClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getClientSecret();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getClientId();
    }
}
