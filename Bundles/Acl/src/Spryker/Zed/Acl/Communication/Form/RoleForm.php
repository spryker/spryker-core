<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\AbstractForm;

class RoleForm extends AbstractForm
{

    const NAME = 'name';
    const ID_ROLE = 'id_acl_role';

    /**
     * @return self
     */
    protected function buildFormFields()
    {
        $this->addName()
            ->addRoleId();

        return $this;
    }

    /**
     * @return self
     */
    protected function addName()
    {
        $this->addText(
            self::NAME,
            [
                'label' => 'Role name',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @return self
     */
    protected function addRoleId()
    {
        $this->addHidden(
            self::ID_ROLE,
            [
                'label' => 'Role name',
            ]
        );

        return $this;
    }

    /**
     * Set the values for fields
     *
     * @return array
     */
    protected function populateFormFields()
    {
    }

    /**
     * @return string
     */
    protected function getFormName()
    {
        return 'role_form';
    }

}
