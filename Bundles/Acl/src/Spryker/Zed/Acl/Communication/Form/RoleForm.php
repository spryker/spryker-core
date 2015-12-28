<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Spryker\Shared\Transfer\TransferInterface;
use Symfony\Component\Form\FormBuilderInterface;

class RoleForm extends AbstractForm
{

    const NAME = 'name';
    const ID_ROLE = 'id_acl_role';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::NAME, 'text', [
            'label' => 'Role name',
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
        ])
        ->add(self::ID_ROLE, 'hidden', [
            'label' => 'Role name',
        ]);
    }

    protected function getDataClass()
    {
        return null;
    }

    public function getName()
    {
        return 'role';
    }


    /**
     * @return self
     */
    protected function addRoleId()
    {


        return $this;
    }

    public function populateFormFields()
    {
        // TODO: Implement populateFormFields() method.
    }


    /**
     * @return string
     */
    protected function getFormName()
    {
        return 'role_form';
    }

}
