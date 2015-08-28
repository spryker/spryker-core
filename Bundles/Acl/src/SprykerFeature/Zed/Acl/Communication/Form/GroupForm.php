<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Form;

use SprykerFeature\Zed\Acl\Communication\Controller\GroupController;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\HttpFoundation\Request;

class GroupForm extends AbstractForm
{

    const FIELD_TITLE = 'title';
    const FIELD_ROLES = 'roles';

    const VALIDATE_ADD = 'add';
    const VALIDATE_EDIT = 'edit';

    protected $queryContainer;

    protected $request;

    public function __construct(AclQueryContainer $queryContainer, Request $request)
    {
        $this->queryContainer = $queryContainer;
        $this->request = $request;
    }

    protected function buildFormFields()
    {
        $this->addText(self::FIELD_TITLE, [
            'constraints' => [
                new Assert\NotBlank([
                    'groups' => [self::VALIDATE_ADD, self::VALIDATE_EDIT]
                ]),
                new Assert\Callback([
                    'groups' => [self::VALIDATE_ADD],
                    'methods' => [
                        function($name, ExecutionContextInterface $contextInterface) {
                            if ($this->queryContainer->queryGroupByName($name)->count() > 0) {
                                $contextInterface->addViolation('Group name already in use');
                            }
                        },
                    ],
                ]),
            ]
        ]);

        $this->add(self::FIELD_ROLES, 'choice', [
            'label'       => 'Customer Group',
            'empty_value' => false,
            'multiple'    => true,
            'choices'     => $this->getAvailableRoleList(),
        ]);
    }

    protected function populateFormFields()
    {
        $defaultData = [
            self::FIELD_TITLE => '',
            self::FIELD_ROLES => [],
        ];

        $idGroup = $this->request->query->get(GroupController::ID_GROUP_PARAMETER);

        if ($idGroup > 0) {
            $group = $this->queryContainer->queryGroupById($idGroup)->findOne();
            $defaultData[self::FIELD_TITLE] = $group->getName();

            $defaultData[self::FIELD_ROLES] = $this->getAvailableRoleListByIdGroup($idGroup);
        }

        return $defaultData;
    }

    public function getAvailableRoleListByIdGroup($idGroup)
    {
        $roleCollection = $this->queryContainer->queryGroupHasRole($idGroup)->find()->toArray();

        return array_column($roleCollection, 'FkAclRole');
    }

    public function getAvailableRoleList()
    {
        $roleCollection = $this->queryContainer->queryRole()->find()->toArray();

        return array_column($roleCollection, 'Name', 'IdAclRole');
    }

}
