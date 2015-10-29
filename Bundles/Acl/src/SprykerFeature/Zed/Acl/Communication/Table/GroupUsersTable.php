<?php

namespace SprykerFeature\Zed\Acl\Communication\Table;

use Orm\Zed\Acl\Persistence\Map\SpyAclGroupTableMap;
use Orm\Zed\Acl\Persistence\Map\SpyAclUserHasGroupTableMap;
use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;

class GroupUsersTable extends AbstractTable
{

    const REMOVE = 'remove';

    const PARAMETER_ID_USER = 'id-user';
    const PARAMETER_ID_GROUP = 'id-group';

    const COL_ID_ACL_GROUP = 'id_acl_group';
    const COL_FK_USER = 'id_user';
    const COL_EMAIL = 'email';
    const COL_FIRST_NAME = 'first_name';
    const COL_LAST_NAME = 'last_name';
    const COL_OPTIONS = 'Options';

    /**
     * @var SpyAclGroupQuery
     */
    protected $hasGroupQuery;

    protected $spyUserQuery;

    /**
     * @var int
     */
    protected $idGroup;

    /**
     * @param SpyAclGroupQuery $hasGroupQuery
     * @param int $idAclGroup
     */
    public function __construct(SpyAclGroupQuery $hasGroupQuery, $idAclGroup)
    {
        $this->hasGroupQuery = $hasGroupQuery;
        $this->idGroup = $idAclGroup;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setUrl('users?' . self::PARAMETER_ID_GROUP . '=' . $this->idGroup);

        $this->disableSearch();
        $this->setTableIdentifier('users-in-group');

        $config->setHeader([
            self::COL_ID_ACL_GROUP => 'ID Acl Group',
            self::COL_FIRST_NAME => 'First Name',
            self::COL_LAST_NAME => 'Last Name',
            self::COL_EMAIL => 'Email',
            self::COL_OPTIONS => 'Options',
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $group = $this->hasGroupQuery->findOneByIdAclGroup(1);

        $query = $this->hasGroupQuery
            ->useSpyAclUserHasGroupQuery()
                ->filterBySpyAclGroup($group)
                ->useSpyUserQuery()
                ->endUse()
            ->endUse()
            ->withColumn(SpyAclUserHasGroupTableMap::COL_FK_USER, self::COL_FK_USER)
            ->withColumn(SpyUserTableMap::COL_FIRST_NAME, self::COL_FIRST_NAME)
            ->withColumn(SpyUserTableMap::COL_LAST_NAME, self::COL_LAST_NAME)
            ->withColumn(SpyUserTableMap::COL_USERNAME, self::COL_EMAIL)
        ;
        $usersResult = $this->runQuery($query, $config);

        $users = [];
        foreach ($usersResult as $user) {
            $users[] = [
                self::COL_ID_ACL_GROUP => $user[SpyAclGroupTableMap::COL_ID_ACL_GROUP],
                self::COL_FK_USER => $user[self::COL_FK_USER],
                self::COL_FIRST_NAME => $user[self::COL_FIRST_NAME],
                self::COL_LAST_NAME => $user[self::COL_LAST_NAME],
                self::COL_EMAIL => $user[self::COL_EMAIL],
                self::COL_OPTIONS => $this->getRemoveUrl($user),
            ];
        }

        return $users;
    }

    /**
     * @param array $user
     *
     * @return string
     */
    protected function getRemoveUrl(array $user)
    {
        return sprintf(
            '<a id="row-%d-%d" href="#" data-options=\'{"idUser": %d, "idGroup": %d}\' class="btn btn-xs btn-danger remove-user-from-group">Remove</a>',
            $user[self::COL_FK_USER],
            $this->idGroup,
            $user[self::COL_FK_USER],
            $this->idGroup
        );
    }

}
