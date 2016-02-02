<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Form\DataProvider;

use Spryker\Zed\Acl\Communication\Form\GroupForm;
use Spryker\Zed\Acl\Persistence\AclQueryContainer;

class AclGroupFormDataProvider
{

    /**
     * @var AclQueryContainer
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Acl\Persistence\AclQueryContainer $queryContainer
     */
    public function __construct(AclQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int|null $idGroup
     *
     * @return array
     */
    public function getData($idGroup = null)
    {
        if (!$idGroup) {
            return [];
        }

        $group = $this->queryContainer
            ->queryGroupById($idGroup)
            ->findOne();

        return [
            GroupForm::FIELD_TITLE => $group->getName(),
            GroupForm::FIELD_ROLES => $this->getAvailableRoleListByIdGroup($idGroup),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            GroupForm::OPTION_ROLE_CHOICES => $this->getAvailableRoleList(),
        ];
    }

    /**
     * @param int $idAclGroup
     *
     * @return array
     */
    protected function getAvailableRoleListByIdGroup($idAclGroup)
    {
        $roleCollection = $this->queryContainer->queryGroupHasRole($idAclGroup)->find()->toArray();

        return array_column($roleCollection, 'FkAclRole');
    }

    /**
     * @return array
     */
    protected function getAvailableRoleList()
    {
        $roleCollection = $this->queryContainer->queryRole()->find()->toArray();

        return array_column($roleCollection, 'Name', 'IdAclRole');
    }

}
