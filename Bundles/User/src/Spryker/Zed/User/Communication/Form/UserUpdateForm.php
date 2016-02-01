<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Form;

use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Dependency\Facade\UserToAclInterface;
use Symfony\Component\Form\FormBuilderInterface;

class UserUpdateForm extends UserForm
{

    /**
     * @var int
     */
    protected $idUser;

    /**
     * @var UserFacade
     */
    protected $userFacade;

    /**
     * @param int $idUser
     * @param \Spryker\Zed\User\Business\UserFacade $userFacade
     * @param \Spryker\Zed\User\Dependency\Facade\UserToAclInterface $aclFacade
     */
    public function __construct($idUser, UserFacade $userFacade, UserToAclInterface $aclFacade)
    {
        parent::__construct($aclFacade);

        $this->idUser = $idUser;
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove(self::FIELD_PASSWORD);

        $builder->add(self::FIELD_STATUS, 'choice', [
            'choices' => $this->getStatusSelectChoices(),
        ]);
    }

    /**
     * Set the values for fields
     *
     * @return array
     */
    public function populateFormFields()
    {
        $userTransfer = $this->userFacade->getUserById($this->idUser);

        $formData = $userTransfer->toArray();
        $formData = $this->populateSelectedAclGroups($formData);

        if (array_key_exists(self::FIELD_PASSWORD, $formData)) {
            unset($formData[self::FIELD_PASSWORD]);
        }

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
                $formData[UserForm::FIELD_GROUP][] = $aclGroupTransfer->getIdAclGroup();
            }
        }

        return $formData;
    }

}
