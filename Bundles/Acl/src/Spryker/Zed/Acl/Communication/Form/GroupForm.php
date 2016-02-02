<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Spryker\Zed\Acl\Persistence\AclQueryContainer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class GroupForm extends AbstractType
{

    const FIELD_TITLE = 'title';
    const FIELD_ROLES = 'roles';

    const VALIDATE_ADD = 'add';
    const VALIDATE_EDIT = 'edit';

    /**
     * @var AclQueryContainer
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Acl\Persistence\AclQueryContainer $queryContainer
     */
    public function __construct(AclQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm222(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_TITLE, 'text', [
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank([
                    'groups' => [self::VALIDATE_ADD, self::VALIDATE_EDIT],
                ]),
                $this->getConstraints()->createConstraintCallback([
                    'groups' => [self::VALIDATE_ADD],
                    'methods' => [
                        function ($name, ExecutionContextInterface $contextInterface) {
                            if ($this->queryContainer->queryGroupByName($name)->count() > 0) {
                                $contextInterface->addViolation('Group name already in use');
                            }
                        },
                    ],
                ]),
            ],
        ])
        ->add(self::FIELD_ROLES, 'choice', [
            'label' => 'Customer Group',
            'empty_value' => false,
            'multiple' => true,
            'choices' => $this->getAvailableRoleList(),
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addTitleField($builder)
            ->addRolesField($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addTitleField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_TITLE, 'text', [
                'label' => 'Title',
                'constraints' => [
                    new NotBlank([
                        'groups' => [self::VALIDATE_ADD, self::VALIDATE_EDIT],
                    ]),
                    new Callback([
                        'groups' => [self::VALIDATE_ADD],
                        'methods' => [
                            function ($name, ExecutionContextInterface $contextInterface) {
                                if ($this->queryContainer->queryGroupByName($name)->count() > 0) {
                                    $contextInterface->addViolation('Group name already in use');
                                }
                            },
                        ],
                    ]),
                ],
            ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addRolesField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_ROLES, new Select2ComboBoxType(), [
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                ],
                'label' => 'Assigned Roles',
                'empty_value' => false,
                'multiple' => true,
                'choices' => $this->getAvailableRoleList(),
            ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'group';
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {

                return [GroupForm::VALIDATE_EDIT];
            },

        ]);
    }

    /**
     * @return array
     */
    protected function getAvailableRoleList()
    {
        $roleCollection = $this->queryContainer->queryRole()->find()->toArray();

        return array_column($roleCollection, 'Name', 'IdAclRole');
    }

}
