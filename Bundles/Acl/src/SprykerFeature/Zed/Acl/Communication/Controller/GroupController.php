<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Controller;

use Generated\Shared\Transfer\GroupTransfer;
use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Communication\AclDependencyContainer;
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
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idGroup = $request->get('id');
        $query = sprintf('?id=%s', $idGroup);

        return $this->viewResponse(['query' => $query]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function formAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createGroupForm(
            $request
        );

        $idGroup = $request->get('id');
        if (!empty($idGroup)) {
            $form->setGroupId($idGroup);
        }

        $statusCode = 200;

        $form->init();

        if ($form->isValid()) {
            $data = $form->getRequestData();
            $group = new GroupTransfer();
            $group->setName($data['name']);

            if (!empty($idGroup)) {
                $group->setIdAclGroup($idGroup);
                $this->getFacade()->updateGroup($group);
            } else {
                $this->getFacade()->addGroup($group->getName());
            }
        }

        return $this->jsonResponse($form->renderData(), $statusCode);
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

    public function rulesetAction(Request $request)
    {

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
