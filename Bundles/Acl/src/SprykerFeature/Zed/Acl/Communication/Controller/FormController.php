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
class FormController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function userAction(Request $request)
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
                $user->setIdUserUser($data['id_user_user']);
                $user = $this->getDependencyContainer()->createUserFacade()
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
                $this->getFacade()->addUserToGroup($user->getIdUserUser(), $data['id_acl_group']);
            }
        }

        return $this->jsonResponse($form->renderData());
    }

}
