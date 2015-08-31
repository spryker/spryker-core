<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Controller;

use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Communication\AclDependencyContainer;
use SprykerFeature\Zed\Acl\Communication\Form\GroupForm;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AclDependencyContainer getDependencyContainer()
 * @method AclFacade getFacade()
 */
class GroupController extends AbstractController
{

    const USER_LIST_URL = '/acl/users';
    const ID_GROUP_PARAMETER = 'id-group';

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->createGroupTable();

        return $this->viewResponse([
            'table' => $table->render(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->createGroupTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createGroupForm($request);
        $form->setOptions([
            'validation_groups' => [GroupForm::VALIDATE_ADD],
        ]);

        $form->handleRequest();

        if ($form->isValid()) {
            $formData = $form->getData();

            $groupTransfer = $this->getFacade()->addGroup($formData[GroupForm::FIELD_TITLE]);

            $this->assignRolesToGroup($groupTransfer->getIdAclGroup(), $formData[GroupForm::FIELD_ROLES]);

            return $this->redirectResponse('/acl/group/edit?' . self::ID_GROUP_PARAMETER . '=' . $groupTransfer->getIdAclGroup());
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idGroup = $request->query->get(self::ID_GROUP_PARAMETER);

        $form = $this->getDependencyContainer()->createGroupForm($request);
        $form->setOptions([
            'validation_groups' => [GroupForm::VALIDATE_EDIT],
        ]);

        $form->handleRequest();

        if ($form->isValid()) {
            $formData = $form->getData();

            $groupTransfer = $this->getFacade()->getGroup($idGroup);
            $groupTransfer->setName($formData[GroupForm::FIELD_TITLE]);

            $groupTransfer = $this->getFacade()->updateGroup($groupTransfer);

            $this->assignRolesToGroup($groupTransfer->getIdAclGroup(), $formData[GroupForm::FIELD_ROLES]);

            return $this->redirectResponse('/acl/group/edit?' . self::ID_GROUP_PARAMETER . '=' . $groupTransfer->getIdAclGroup());
        }

        $usersTable = $this->getDependencyContainer()->createGroupUsersTable($idGroup);

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param int $idGroup
     * @param array $rolesArray
     */
    protected function assignRolesToGroup($idGroup, array $rolesArray)
    {
        $this->getFacade()->assignRolesToGroup($idGroup, $rolesArray);
    }

    /**
     * @return GroupTable
     */
    protected function createGroupTable()
    {
        return $this->getDependencyContainer()->createGroupTable();
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function rolesAction(Request $request)
    {
        $idGroup = $request->get(self::ID_GROUP_PARAMETER);

        $roles = $this->getDependencyContainer()->createGroupRoleListByGroupId($idGroup);

        return $this->jsonResponse($roles);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->createGroupsGrid($request);
        $data = $grid->renderData();

        return $this->jsonResponse($data);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function rulesAction(Request $request)
    {
        $idGroup = $request->get('id');
        $grid = $this->getDependencyContainer()->createRulesetGrid($request, $idGroup);

        $data = $grid->renderData();

        return $this->jsonResponse($data);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function usersAction(Request $request)
    {
        $idGroup = $request->get('id');
        $grid = $this->getDependencyContainer()->createUserGridByGroupId($request, $idGroup);
        $data = $grid->renderData();

        return $this->jsonResponse($data);
    }

}
