<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Plugin;

use Generated\Shared\Transfer\ButtonTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CustomerUserConnectorGui\Communication\Controller\EditController as ControllerEditController;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\User\Communication\Controller\EditController;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableActionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CustomerUserConnectorGui\Communication\CustomerUserConnectorGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerUserConnectorGui\CustomerUserConnectorGuiConfig getConfig()
 */
class UserTableActionExpanderPlugin extends AbstractPlugin implements UserTableActionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $user
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function getActionButtonDefinitions(array $user)
    {
        return [
            $this->getEditCustomerUserConnectionButton($user),
        ];
    }

    /**
     * @param array $user
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function getEditCustomerUserConnectionButton(array $user)
    {
        return (new ButtonTransfer())
            ->setUrl($this->getEditCustomerUserConnectionUrl($user[SpyUserTableMap::COL_ID_USER]))
            ->setTitle('Assign Customers')
            ->setDefaultOptions([
                'class' => 'btn-edit',
                'icon' => 'fa-pencil-square-o',
            ]);
    }

    /**
     * @param int $idUser
     *
     * @return string
     */
    protected function getEditCustomerUserConnectionUrl($idUser)
    {
        return Url::generate(
            ControllerEditController::PAGE_EDIT,
            [
                EditController::PARAM_ID_USER => $idUser,
            ]
        );
    }
}
