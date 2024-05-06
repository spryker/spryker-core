<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Form\DataProvider;

use Spryker\Zed\Acl\Communication\Form\GroupForm;
use Spryker\Zed\Acl\Persistence\AclQueryContainerInterface;

class AclGroupFormDataProvider
{
    /**
     * @var \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface $queryContainer
     */
    public function __construct(AclQueryContainerInterface $queryContainer)
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

        if (!$group) {
            return [];
        }

        return [
            GroupForm::FIELD_TITLE => $group->getName(),
            GroupForm::FIELD_ROLES => $this->getAvailableRoleListByIdGroup($idGroup),
        ];
    }

    /**
     * @return array<string, mixed>
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
        /** @var \Propel\Runtime\Collection\ObjectCollection $roleCollection */
        $roleCollection = $this->queryContainer->queryGroupHasRole($idAclGroup)->find();

        return array_column($roleCollection->toArray(), 'FkAclRole');
    }

    /**
     * @return array
     */
    protected function getAvailableRoleList()
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $roleCollection */
        $roleCollection = $this->queryContainer->queryRole()->find();

        return array_column($roleCollection->toArray(), 'IdAclRole', 'Name');
    }
}
