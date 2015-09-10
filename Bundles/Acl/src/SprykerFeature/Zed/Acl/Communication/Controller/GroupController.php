<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Controller;

use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Business\Exception\UserAndGroupNotFoundException;
use SprykerFeature\Zed\Acl\Communication\AclDependencyContainer;
use SprykerFeature\Zed\Acl\Communication\Form\GroupForm;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method AclDependencyContainer getDependencyContainer()
 * @method AclFacade getFacade()
 */
class GroupController extends AbstractController
{

    const USER_LIST_URL = '/acl/users';
    const PARAMETER_ID_GROUP = 'id-group';
    const PARAMETER_ID_USER = 'id-user';

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

            return $this->redirectResponse('/acl/group/edit?' . self::PARAMETER_ID_GROUP . '=' . $groupTransfer->getIdAclGroup());
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
        $idGroup = $request->query->get(self::PARAMETER_ID_GROUP);

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

            return $this->redirectResponse('/acl/group/edit?' . self::PARAMETER_ID_GROUP . '=' . $groupTransfer->getIdAclGroup());
        }

        $usersTable = $this->getDependencyContainer()->createGroupUsersTable($idGroup);

        return $this->viewResponse([
            'form' => $form->createView(),
            'users' => $usersTable->render(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function usersAction(Request $request)
    {
        $idGroup = $request->query->get(self::PARAMETER_ID_GROUP);

        $usersTable = $this->getDependencyContainer()->createGroupUsersTable($idGroup);

        return $this->jsonResponse(
            $usersTable->fetchData()
        );
    }

    public function removeUserFromGroupAction(Request $request)
    {
        $idGroup = (int) $request->request->get(self::PARAMETER_ID_GROUP);
        $idUser = (int) $request->request->get(self::PARAMETER_ID_USER);

        try {
            $this->getFacade()->removeUserFromGroup($idUser, $idGroup);
            $response = [
                'code' => Response::HTTP_OK,
                'id-group' => $idGroup,
                'id-user' => $idUser,
            ];
        } catch (UserAndGroupNotFoundException $e) {
            $response = [
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'User and group not found',
            ];
        }

        return $this->jsonResponse($response);
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
        $idGroup = $request->get(self::PARAMETER_ID_GROUP);

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

}
