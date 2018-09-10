<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Table;

use Orm\Zed\User\Persistence\SpyUser;
use Spryker\Zed\Acl\Persistence\AclQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class GroupUsersTable extends AbstractTable
{
    const REMOVE = 'remove';

    const PARAMETER_ID_USER = 'id-user';
    const PARAMETER_ID_GROUP = 'id-group';

    const COL_ID_ACL_GROUP = 'id_acl_group';
    const COL_ID_USER = 'id_user';
    const COL_EMAIL = 'email';
    const COL_FIRST_NAME = 'first_name';
    const COL_LAST_NAME = 'last_name';
    const ACTIONS = 'Actions';

    /**
     * @var \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface
     */
    protected $aclQueryContainer;

    /**
     * @var int
     */
    protected $idGroup;

    /**
     * @param \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface $aclQueryContainer
     * @param int $idAclGroup
     */
    public function __construct(AclQueryContainerInterface $aclQueryContainer, $idAclGroup)
    {
        $this->aclQueryContainer = $aclQueryContainer;
        $this->idGroup = $idAclGroup;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setUrl('users?' . self::PARAMETER_ID_GROUP . '=' . $this->idGroup);

        $this->disableSearch();
        $this->setTableIdentifier('users-in-group');

        $config->setHeader([
            self::COL_FIRST_NAME => 'First Name',
            self::COL_LAST_NAME => 'Last Name',
            self::COL_EMAIL => 'Email',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->aclQueryContainer->queryGroupUsers($this->idGroup);

        /** @var \Orm\Zed\User\Persistence\SpyUser[]|\Propel\Runtime\Collection\ObjectCollection $userCollection */
        $userCollection = $this->runQuery($query, $config, true);

        $users = [];
        foreach ($userCollection as $user) {
            $users[] = [
                self::COL_ID_USER => $user->getIdUser(),
                self::COL_FIRST_NAME => $user->getFirstName(),
                self::COL_LAST_NAME => $user->getLastName(),
                self::COL_EMAIL => $user->getUsername(),
                self::ACTIONS => $this->getRemoveUrl($user),
            ];
        }

        return $users;
    }

    /**
     * @param \Orm\Zed\User\Persistence\SpyUser $user
     *
     * @return string
     */
    protected function getRemoveUrl(SpyUser $user)
    {
        return $this->generateRemoveButton('/acl/group/delete-user-from-group', 'Delete', [
            'id-user' => $user->getIdUser(),
            'id-group' => $this->idGroup,
        ]);
    }
}
