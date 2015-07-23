<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Communication\AclDependencyContainer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AclDependencyContainer getDependencyContainer()
 * @method AclFacade getFacade()
 */
class UsersController extends AbstractController
{

    const USER_LIST_URL = '/acl/users';

    public function indexAction()
    {
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function manageAction(Request $request)
    {
        $data = [];
        $idUser = $request->get('id');

        if (!empty($idUser)) {
            $data['query'] = sprintf('?id=%s', $idUser);
        }

        return $this->viewResponse($data);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->createUserGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function formAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createUserWithGroupForm($request);

        $idUser = $request->get('id');

        if (false === empty($idUser)) {
            $form->setUserId($idUser);
        }

        $form->init();

        if ($form->isValid()) {
            $data = $form->getRequestData();

            $userGroup = false;

            $user = new UserTransfer();
            $user->setFirstName($data['first_name'])
                ->setLastName($data['last_name'])
                ->setUsername($data['username'])
                ->setPassword($data['password'])
            ;

            if (false === empty($idUser)) {
                $user->setIdUser($data['id_user']);
                $user = $this->getDependencyContainer()
                    ->createUserFacade()
                    ->updateUser($user)
                ;

                $userGroup = $this->getFacade()
                    ->getUserGroup($idUser)
                ;

                if ($userGroup->getIdAclGroup() !== $data['id_acl_group']) {
                    $this->getFacade()
                        ->removeUserFromGroup($idUser, $userGroup->getIdAclGroup())
                    ;
                }
            } else {
                $user = $this->getDependencyContainer()->createUserFacade()
                    ->addUser(
                        $user->getFirstName(),
                        $user->getLastName(),
                        $user->getUsername(),
                        $user->getPassword()
                    )
                ;
            }

            if ($userGroup === false || $userGroup->getIdAclGroup() !== $data['id_acl_group']) {
                $this->getFacade()->addUserToGroup($user->getIdUser(), $data['id_acl_group']);
            }
        }

        return $this->jsonResponse($form->renderData());
    }

}
