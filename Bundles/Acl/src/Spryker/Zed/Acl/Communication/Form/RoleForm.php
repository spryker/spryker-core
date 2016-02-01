<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RoleForm extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_ID_ROLE = 'id_acl_role';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addNameField($builder)
            ->addRoleField($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_NAME, 'text', [
                'label' => 'Name',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addRoleField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_ID_ROLE, 'hidden', [
                'label' => 'Role name',
            ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'role';
    }

}
