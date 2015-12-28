<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Spryker\Zed\Acl\Communication\Controller\GroupController;
use Spryker\Zed\Acl\Persistence\AclQueryContainer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\HttpFoundation\Request;

class GroupForm extends AbstractForm
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
     * @var Request
     */
    protected $request;

    /**
     * @param AclQueryContainer $queryContainer
     * @param Request $request
     */
    public function __construct(AclQueryContainer $queryContainer, Request $request)
    {
        $this->queryContainer = $queryContainer;
        $this->request = $request;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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
        return 'group';
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $defaultData = [
            self::FIELD_TITLE => '',
            self::FIELD_ROLES => [],
        ];

        $idGroup = $this->request->query->get(GroupController::PARAMETER_ID_GROUP);

        if ($idGroup > 0) {
            $group = $this->queryContainer->queryGroupById($idGroup)->findOne();
            $defaultData[self::FIELD_TITLE] = $group->getName();

            $defaultData[self::FIELD_ROLES] = $this->getAvailableRoleListByIdGroup($idGroup);
        }

        return $defaultData;
    }

    /**
     * @param int $idAclGroup
     *
     * @return array
     */
    public function getAvailableRoleListByIdGroup($idAclGroup)
    {
        $roleCollection = $this->queryContainer->queryGroupHasRole($idAclGroup)->find()->toArray();

        return array_column($roleCollection, 'FkAclRole');
    }

    /**
     * @return array
     */
    public function getAvailableRoleList()
    {
        $roleCollection = $this->queryContainer->queryRole()->find()->toArray();

        return array_column($roleCollection, 'Name', 'IdAclRole');
    }

}
