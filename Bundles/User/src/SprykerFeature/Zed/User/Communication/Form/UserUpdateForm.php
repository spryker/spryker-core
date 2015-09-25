<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Form;

class UserUpdateForm extends UserForm
{
    /**
     * @var integer
     */
    private $idUser;

    /**
     * @param integer $idUser
     */
    public function __construct($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * @return self
     */
    protected function buildFormFields()
    {
        parent::buildFormFields();

        $this->addUserStatus();

        return $this;

    }

    /**
     * Set the values for fields
     *
     * @return array
     */
    protected function populateFormFields()
    {
        $userFacade = $this->getLocator()->user()->facade();
        $userTransfer = $userFacade->getUserById($this->idUser);

        $formData = $userTransfer->toArray();
        $formData = $this->populateSelectedAclGroups($formData);

        return $formData;
    }

    /**
     * @param array $formData
     *
     * @return array
     */
    protected function populateSelectedAclGroups(array $formData)
    {
        $aclFacade = $this->getLocator()->acl()->facade();
        $userAclGroupsTransfer = $aclFacade->getUserGroups($this->idUser);

        $groupChoices = $this->getGroupChoices();
        foreach ($userAclGroupsTransfer->getGroups() as $aclGroupTransfer) {
            if (array_key_exists($aclGroupTransfer->getIdAclGroup(), $groupChoices)) {
                $formData[UserForm::GROUP][] = $aclGroupTransfer->getIdAclGroup();
            }
        }
        return $formData;
    }
}
