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
class RulesetController extends AbstractController
{

    const USER_LIST_URL = '/acl/users';

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
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
    public function rulesAction(Request $request)
    {
        $idGroup = $request->get('id');
        $grid = $this->getDependencyContainer()->createRulesetGrid($request, $idGroup);

        $data = $grid->renderData();

        return $this->jsonResponse($data);
    }

    public function usersAction(Request $request)
    {
        $idGroup = $request->get('id');
        $grid = $this->getDependencyContainer()->createUserGridByGroupId($request, $idGroup);
        $data = $grid->renderData();

        return $this->jsonResponse($data);
    }

}
