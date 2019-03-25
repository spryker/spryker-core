<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Form\DataProvider;

use Spryker\Zed\User\Business\UserFacadeInterface;
use Spryker\Zed\User\Communication\Form\UserForm;
use Spryker\Zed\User\Dependency\Plugin\GroupPluginInterface;

class UserFormDataProvider
{
    /**
     * @var \Spryker\Zed\User\Dependency\Plugin\GroupPluginInterface
     */
    protected $groupPlugin;

    /**
     * @var \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\User\Dependency\Plugin\GroupPluginInterface $groupPlugin
     * @param \Spryker\Zed\User\Business\UserFacadeInterface $userFacade
     */
    public function __construct(GroupPluginInterface $groupPlugin, UserFacadeInterface $userFacade)
    {
        $this->groupPlugin = $groupPlugin;
        $this->userFacade = $userFacade;
    }

    /**
     * @param int $idUser
     *
     * @return array|null
     */
    public function getData($idUser)
    {
        $userTransfer = $this->userFacade->findUserById($idUser);

        if ($userTransfer === null) {
            return null;
        }

        $formData = $userTransfer->toArray();
        $formData = $this->populateSelectedAclGroups($idUser, $formData);

        if (array_key_exists(UserForm::FIELD_PASSWORD, $formData)) {
            unset($formData[UserForm::FIELD_PASSWORD]);
        }

        return $formData;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            UserForm::OPTION_GROUP_CHOICES => $this->getGroupChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function getGroupChoices()
    {
        $groupCollection = [];
        $groupsTransfer = $this->groupPlugin->getAllGroups();

        foreach ($groupsTransfer->getGroups() as $groupTransfer) {
            $groupCollection[$groupTransfer->getIdAclGroup()] = $this->formatGroupName($groupTransfer->getName());
        }

        return $groupCollection;
    }

    /**
     * @param string $groupName
     *
     * @return string
     */
    protected function formatGroupName($groupName)
    {
        return str_replace('_', ' ', ucfirst($groupName));
    }

    /**
     * @param int $idUser
     * @param array $formData
     *
     * @return array
     */
    protected function populateSelectedAclGroups($idUser, array $formData)
    {
        $userAclGroupsTransfer = $this->groupPlugin->getUserGroups($idUser);
        $groupChoices = $this->getGroupChoices();

        foreach ($userAclGroupsTransfer->getGroups() as $aclGroupTransfer) {
            if (array_key_exists($aclGroupTransfer->getIdAclGroup(), $groupChoices)) {
                $formData[UserForm::FIELD_GROUP][] = $aclGroupTransfer->getIdAclGroup();
            }
        }

        return $formData;
    }
}
