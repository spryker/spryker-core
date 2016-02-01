<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Controller;

use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\Acl\Business\AclFacade;
use Spryker\Zed\Acl\Business\Exception\UserAndGroupNotFoundException;
use Spryker\Zed\Acl\Communication\AclCommunicationFactory;
use Spryker\Zed\Acl\Communication\Form\GroupForm;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method AclCommunicationFactory getFactory()
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
        $table = $this->getFactory()->createGroupTable();

        return $this->viewResponse([
            'table' => $table->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createGroupTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()->createGroupForm($request, [
            'validation_groups' => [GroupForm::VALIDATE_ADD],
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $roles = $this->getRoleTransfersFromForm($form);

            $groupTransfer = $this->getFacade()->addGroup(
                $formData[GroupForm::FIELD_TITLE],
                $roles
            );

            return $this->redirectResponse('/acl/group/edit?' . self::PARAMETER_ID_GROUP . '=' . $groupTransfer->getIdAclGroup());
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idAclGroup = $request->query->get(self::PARAMETER_ID_GROUP);

        $form = $this->getFactory()->createGroupForm($request, [
            'validation_groups' => [GroupForm::VALIDATE_EDIT],
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $roles = $this->getRoleTransfersFromForm($form);

            $groupTransfer = $this->getFacade()->getGroup($idAclGroup);
            $groupTransfer->setName($formData[GroupForm::FIELD_TITLE]);
            $groupTransfer = $this->getFacade()
                ->updateGroup(
                    $groupTransfer,
                    $roles
                );

            return $this->redirectResponse('/acl/group/edit?' . self::PARAMETER_ID_GROUP . '=' . $groupTransfer->getIdAclGroup());
        }

        $usersTable = $this->getFactory()->createGroupUsersTable($idAclGroup);

        return $this->viewResponse([
            'form' => $form->createView(),
            'users' => $usersTable->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\Form $form
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    protected function getRoleTransfersFromForm(Form $form)
    {
        $roles = new RolesTransfer();
        $formData = $form->getData();

        foreach ($formData[GroupForm::FIELD_ROLES] as $idRole) {
            $roleTransfer = (new RoleTransfer())
                ->setIdAclRole($idRole);
            $roles->addRole($roleTransfer);
        }

        return $roles;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function usersAction(Request $request)
    {
        $idGroup = $request->query->get(self::PARAMETER_ID_GROUP);

        $usersTable = $this->getFactory()->createGroupUsersTable($idGroup);

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function rolesAction(Request $request)
    {
        $idGroup = $request->get(self::PARAMETER_ID_GROUP);

        $roles = $this->getFactory()->getGroupRoleListByGroupId($idGroup);

        return $this->jsonResponse($roles);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listAction(Request $request)
    {
        $grid = $this->getFactory()->createGroupsGrid($request);
        $data = $grid->renderData();

        return $this->jsonResponse($data);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function rulesAction(Request $request)
    {
        $idGroup = $request->get('id');
        $grid = $this->getFactory()->createRulesetGrid($request, $idGroup);

        $data = $grid->renderData();

        return $this->jsonResponse($data);
    }

}
