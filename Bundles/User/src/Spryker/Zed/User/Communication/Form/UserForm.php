<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Form;

use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserForm extends AbstractType
{

    const OPTION_GROUP_CHOICES = 'group_choices';

    const FIELD_USERNAME = 'username';
    const FIELD_GROUP = 'group';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_PASSWORD = 'password';
    const FIELD_STATUS = 'status';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addUsernameField($builder)
            ->addPasswordField($builder)
            ->addFirstNameField($builder)
            ->addLastNameField($builder)
            ->addGroupField($builder, $options[self::OPTION_GROUP_CHOICES]);
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addUsernameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_USERNAME, 'text', [
                'label' => 'Username',
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
    protected function addPasswordField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_PASSWORD, 'repeated', [
                'constraints' => [
                    new NotBlank(),
                ],
                'invalid_message' => 'The password fields must match.',
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'required' => true,
                'type' => 'password',
            ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addFirstNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_FIRST_NAME, 'text', [
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
    protected function addLastNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_LAST_NAME, 'text', [
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
    protected function addGroupField(FormBuilderInterface $builder, array $choices)
    {
        $builder
            ->add(self::FIELD_GROUP, 'choice', [
                'constraints' => [
                    new Choice([
                        'choices' => array_keys($choices),
                        'multiple' => true,
                        'min' => 1,
                    ]),
                ],
                'label' => 'Assigned groups',
                'multiple' => true,
                'expanded' => true,
                'choices' => $choices,
            ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_GROUP_CHOICES);
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
     * @return array
     */
    protected function getStatusSelectChoices()
    {
        return array_combine(
            SpyUserTableMap::getValueSet(SpyUserTableMap::COL_STATUS),
            SpyUserTableMap::getValueSet(SpyUserTableMap::COL_STATUS)
        );
    }

}
