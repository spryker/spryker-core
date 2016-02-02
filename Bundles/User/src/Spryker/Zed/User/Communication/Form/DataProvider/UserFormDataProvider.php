<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Form\DataProvider;

use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Communication\Form\UserForm;
use Spryker\Zed\User\Dependency\Facade\UserToAclInterface;

class UserFormDataProvider
{

    /**
     * @var array
     */
    protected $groupCollectionCache;

    /**
     * @var UserToAclInterface
     */
    protected $aclFacade;

    /**
     * @var UserFacade
     */
    protected $userFacade;

    /**
     * @param UserToAclInterface $aclFacade
     * @param UserFacade $userFacade
     */
    public function __construct(UserToAclInterface $aclFacade, UserFacade $userFacade)
    {
        $this->aclFacade = $aclFacade;
        $this->userFacade = $userFacade;
    }

    /**
     * @param int $idUser
     *
     * @return array
     */
    public function getData($idUser)
    {
        $userTransfer = $this->userFacade->getUserById($idUser);
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
        if ($this->groupCollectionCache === null) {
            $groupsTransfer = $this->aclFacade->getAllGroups();

            foreach ($groupsTransfer->getGroups() as $groupTransfer) {
                $this->groupCollectionCache[$groupTransfer->getIdAclGroup()] = $this->formatGroupName($groupTransfer->getName());
            }
        }

        return $this->groupCollectionCache;
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
        $userAclGroupsTransfer = $this->aclFacade->getUserGroups($idUser);
        $groupChoices = $this->getGroupChoices();

        foreach ($userAclGroupsTransfer->getGroups() as $aclGroupTransfer) {
            if (array_key_exists($aclGroupTransfer->getIdAclGroup(), $groupChoices)) {
                $formData[UserForm::FIELD_GROUP][] = $aclGroupTransfer->getIdAclGroup();
            }
        }

        return $formData;
    }

}
