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
    /**
     * @var string
     */
    public const REMOVE = 'remove';

    /**
     * @var string
     */
    public const PARAMETER_ID_USER = 'id-user';

    /**
     * @var string
     */
    public const PARAMETER_ID_GROUP = 'id-group';

    /**
     * @var string
     */
    public const COL_ID_ACL_GROUP = 'id_acl_group';

    /**
     * @var string
     */
    public const COL_ID_USER = 'id_user';

    /**
     * @var string
     */
    public const COL_EMAIL = 'email';

    /**
     * @var string
     */
    public const COL_FIRST_NAME = 'first_name';

    /**
     * @var string
     */
    public const COL_LAST_NAME = 'last_name';

    /**
     * @var string
     */
    public const ACTIONS = 'Actions';

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
        $config->setUrl('users?' . static::PARAMETER_ID_GROUP . '=' . $this->idGroup);

        $this->disableSearch();
        $this->setTableIdentifier('users-in-group');

        $config->setHeader([
            static::COL_FIRST_NAME => 'First Name',
            static::COL_LAST_NAME => 'Last Name',
            static::COL_EMAIL => 'Email',
            static::ACTIONS => static::ACTIONS,
        ]);

        $config->addRawColumn(static::ACTIONS);

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

        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\User\Persistence\SpyUser[] $userCollection */
        $userCollection = $this->runQuery($query, $config, true);

        $users = [];
        foreach ($userCollection as $user) {
            $users[] = [
                static::COL_ID_USER => $user->getIdUser(),
                static::COL_FIRST_NAME => $user->getFirstName(),
                static::COL_LAST_NAME => $user->getLastName(),
                static::COL_EMAIL => $user->getUsername(),
                static::ACTIONS => $this->getRemoveUrl($user),
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
