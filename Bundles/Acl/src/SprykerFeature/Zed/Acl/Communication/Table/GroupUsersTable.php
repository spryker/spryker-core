<?php

namespace SprykerFeature\Zed\Acl\Communication\Table;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Acl\Persistence\Propel\Base\SpyAclUserHasGroupQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\Map\SpyAclGroupTableMap;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroupQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\User\Persistence\Propel\Map\SpyUserTableMap;
use SprykerFeature\Zed\User\Persistence\Propel\SpyUserQuery;

class GroupUsersTable extends AbstractTable
{

    const REMOVE = 'remove';

    const PARAMETER_ID_USER = 'id-user';
    const PARAMETER_ID_GROUP = 'id-group';

    const COL_FIRST_NAME = 'spy_user.first_name';
    const COL_LAST_NAME = 'spy_user.last_name';
    const COL_USERNAME = 'spy_user.username';
    const COL_ID_USER = 'spy_user.id_user';
    const COL_EMAIL = 'email';

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
     * @param SpyUserQuery $spyUserQuery
     * @param int $idGroup
     */
    public function __construct(SpyAclGroupQuery $hasGroupQuery, SpyUserQuery $spyUserQuery, $idGroup)
    {
        $this->hasGroupQuery = $hasGroupQuery;
        $this->spyUserQuery = $spyUserQuery;
        $this->idGroup = $idGroup;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setUrl('users?' . self::PARAMETER_ID_GROUP . '=' . $this->idGroup);

        $this->tableClass = 'gui-table-data-no-search';
        $this->setTableIdentifier('users-in-group');

        $config->setHeader([
            self::COL_ID_USER => 'ID User',
            self::COL_USERNAME => 'Username',
            self::COL_EMAIL => 'Email',
            self::REMOVE => 'Remvoe',
            'id_acl_group' => 'Group',
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
//        dump($this->hasGroupQuery);
//        die;

        $query = $this->hasGroupQuery
//            ->withColumn(SpyUserTableMap::COL_FIRST_NAME, self::COL_FIRST_NAME)
//            ->withColumn(SpyUserTableMap::COL_LAST_NAME, self::COL_LAST_NAME)
//            ->withColumn(SpyUserTableMap::COL_LAST_NAME, self::COL_EMAIL)
//            ->useSpyAclUserHasGroupQuery()
//                ->useSpyUserQuery()
//                    ->withColumn(SpyUserTableMap::COL_USERNAME, self::COL_EMAIL)
//                    ->withColumn(SpyUserTableMap::COL_ID_USER, self::COL_ID_USER)
//                ->endUse()
//            ->endUse()
//            ->filterByIdAclGroup($this->idGroup)
        ;

        $usersResult = $this->runQuery($query, $config);
dump($usersResult);
//die;
        $users = [];
        foreach ($usersResult as $user) {
            $users[] = [
                self::COL_ID_USER => $user[self::COL_ID_USER],
                'id_acl_group' => $user['id_acl_group'],
//                self::COL_USERNAME => $user[self::COL_FIRST_NAME] . ' ' . $user[self::COL_LAST_NAME],
                self::COL_USERNAME => $user[SpyUserTableMap::COL_FIRST_NAME] . ' ' . $user[SpyUserTableMap::COL_LAST_NAME],
                self::COL_EMAIL => $user[SpyUserTableMap::COL_USERNAME],
                self::REMOVE => $this->getRemoveUrl($user),
            ];
        }

        dump($users);
        die;

        return $users;
    }

    /**
     * @param array $user
     *
     * @return string
     */
    protected function getRemoveUrl(array $user)
    {
//        return 'remove';

        return sprintf(
            '<a id="row-%d-%d" href="#" data-options=\'{"idUser": %d, "idGroup": %d}\' class="btn btn-xs btn-danger remove-user-from-group">Remove</a>',
            $user[self::COL_ID_USER],
            $this->idGroup,
            $user[self::COL_ID_USER],
            $this->idGroup
        );
    }

}
