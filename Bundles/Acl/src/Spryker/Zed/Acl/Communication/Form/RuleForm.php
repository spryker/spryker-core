<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RuleForm extends AbstractType
{

    const OPTION_TYPE = 'option_type';

    const FIELD_BUNDLE = 'bundle';
    const FIELD_CONTROLLER = 'controller';
    const FIELD_ACTION = 'action';
    const FIELD_TYPE = 'type';
    const FIELD_FK_ACL_ROLE = 'fk_acl_role';

    /**
     * @return string
     */
    public function getName()
    {
        return 'ruleset';
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public /**
     * @return void
     */
    function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_TYPE);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public /**
     * @return void
     */
    function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addBundleField($builder)
            ->addControllerField($builder)
            ->addActionField($builder)
            ->addPermissionField($builder, $options[self::OPTION_TYPE])
            ->addRoleFkField($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addBundleField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_BUNDLE, 'text', [
            'label' => 'Bundle',
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
    protected function addControllerField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CONTROLLER, 'text', [
            'label' => 'Controller',
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
    protected function addActionField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ACTION, 'text', [
            'label' => 'Action',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $choices
     *
     * @return self
     */
    protected function addPermissionField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_TYPE, 'choice', [
            'label' => 'Permission',
            'choices' => $choices,
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addRoleFkField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FK_ACL_ROLE, 'hidden', []);

        return $this;
    }

}
