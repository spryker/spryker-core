<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Form;

use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Dependency\Facade\UserToAclInterface;

class UserUpdateForm extends UserForm
{

    /**
     * @var int
     */
    private $idUser;

    /**
     * @var UserFacade
     */
    protected $userFacade;

    /**
     * @param int $idUser
     * @param UserFacade $userFacade
     * @param UserToAclInterface $aclFacade
     */
    public function __construct($idUser, UserFacade $userFacade, UserToAclInterface $aclFacade)
    {
        parent::__construct($aclFacade);

        $this->idUser = $idUser;
        $this->userFacade = $userFacade;
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
        $userTransfer = $this->userFacade->getUserById($this->idUser);

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
        $userAclGroupsTransfer = $this->aclFacade->getUserGroups($this->idUser);

        $groupChoices = $this->getGroupChoices();
        foreach ($userAclGroupsTransfer->getGroups() as $aclGroupTransfer) {
            if (array_key_exists($aclGroupTransfer->getIdAclGroup(), $groupChoices)) {
                $formData[UserForm::GROUP][] = $aclGroupTransfer->getIdAclGroup();
            }
        }

        return $formData;
    }

}
