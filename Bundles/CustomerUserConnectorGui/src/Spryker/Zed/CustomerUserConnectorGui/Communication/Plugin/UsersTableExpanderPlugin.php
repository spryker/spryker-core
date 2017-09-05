<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Plugin;

use Generated\Shared\Transfer\ButtonTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\User\Communication\Controller\EditController;
use Spryker\Zed\User\Dependency\Plugin\UsersTableExpanderPluginInterface;

class UsersTableExpanderPlugin implements UsersTableExpanderPluginInterface
{

    /**
     * @param array $user
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function getActionButtonDefinitions(array $user)
    {
        return [
            (new ButtonTransfer())
                ->setUrl($this->getEditCustomerUserConnectionUrl($user[SpyUserTableMap::COL_ID_USER]))
                ->setTitle('Assigned Customers')
                ->setDefaultOptions([
                    'class' => 'btn-edit',
                    'icon' => 'fa-pencil-square-o',
                ]),
        ];
    }

    /**
     * @param int $idUser
     *
     * @return string
     */
    protected function getEditCustomerUserConnectionUrl($idUser)
    {
        return Url::generate('/customer-user-connector-gui/edit', [
            EditController::PARAM_ID_USER => $idUser,
        ]);
    }

}
