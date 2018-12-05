<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Plugin\CompanyUserGui;

use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableGetDeleteLinkPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig getConfig()
 */
class CompanyUserTableGetDeleteLinkPlugin extends AbstractPlugin implements CompanyUserTableGetDeleteLinkPluginInterface
{
    protected const URL_CONFIRM_DELETE_COMPANY_USER = '/business-on-behalf-gui/delete-company-user/confirm-delete';

    /**
     * {@inheritdoc}
     * - Returns delete company link through BusinessOnBehalfGui module.
     *
     * @api
     *
     * @return string
     */
    public function getLink(): string
    {
        return static::URL_CONFIRM_DELETE_COMPANY_USER;
    }
}
