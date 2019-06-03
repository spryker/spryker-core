<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Communication\Plugin\Filter;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface;

/**
 * @method \Spryker\Zed\OauthPermission\Business\OauthPermissionFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthPermission\OauthPermissionConfig getConfig()
 * @method \Spryker\Zed\OauthPermission\Communication\OauthPermissionCommunicationFactory getFactory()
 */
class OauthUserIdentifierFilterPermissionPlugin extends AbstractPlugin implements OauthUserIdentifierFilterPluginInterface
{
    /**
     * {@inheritdoc}
     * - Filters user identifier array to remove configured in
     * \Spryker\Zed\OauthPermission\OauthPermissionConfig::getOauthUserIdentifierFilterKeys() keys.
     *
     * @api
     *
     * @param array $userIdentifier
     *
     * @return array
     */
    public function filter(array $userIdentifier): array
    {
        return $this->getFacade()->filterOauthUserIdentifier($userIdentifier);
    }
}
