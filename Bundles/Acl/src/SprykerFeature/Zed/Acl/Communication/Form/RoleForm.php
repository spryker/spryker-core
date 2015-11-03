<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

class RoleForm extends AbstractForm
{

    const NAME = 'name';
    const ID_ROLE = 'id_acl_role';

    /**
     * @return $this
     */
    protected function buildFormFields()
    {
        $this->addName()
            ->addRoleId();

        return $this;
    }

    /**
     * @return $this
     */
    protected function addName()
    {
        $this->addText(
            self::NAME,
            [
                'label' => 'Role name',
                'constraints' => [
                    $this->locateConstraint()->createConstraintNotBlank(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @return $this
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
