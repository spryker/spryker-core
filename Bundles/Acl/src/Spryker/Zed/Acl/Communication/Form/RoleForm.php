<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class RoleForm extends AbstractForm
{

    const FIELD_NAME = 'name';
    const FIELD_ID_ROLE = 'id_acl_role';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_NAME, 'text', [
            'label' => 'Role name',
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
        ])
        ->add(self::FIELD_ID_ROLE, 'hidden', [
            'label' => 'Role name',
        ]);
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
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

    /**
     * @return array
     */
    public function populateFormFields()
    {
        return [];
    }

}
