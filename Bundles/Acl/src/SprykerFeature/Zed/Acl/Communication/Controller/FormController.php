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

    const ID = 'id';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const ID_ACL_GROUP = 'id_acl_group';

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function userAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createUserWithGroupForm($request);

        $idUser = $request->get(self::ID);

        if (false === empty($idUser)) {
            $form->setUserId($idUser);
        }

        $form->init();

        if ($form->isValid()) {
            $data = $form->getRequestData();

            $userGroup = false;

            $user = new UserTransfer();
            $user->setFirstName($data[self::FIRST_NAME])
                ->setLastName($data[self::LAST_NAME])
                ->setUsername($data[self::USERNAME])
                ->setPassword($data[self::PASSWORD])
            ;

            if (false === empty($idUser)) {
                $user->setIdUser($data[self::ID_USER]);
                $user = $this->getDependencyContainer()->createUserFacade()
                    ->updateUser($user)
                ;

                $userGroup = $this->getFacade()
                    ->getUserGroup($idUser)
                ;

                if ($userGroup->getIdAclGroup() !== $data[self::ID_ACL_GROUP]) {
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

            if ($userGroup === false || $userGroup->getIdAclGroup() !== $data[self::ID_ACL_GROUP]) {
                $this->getFacade()->addUserToGroup($user->getIdUser(), $data[self::ID_ACL_GROUP]);
            }
        }

        return $this->jsonResponse($form->renderData());
    }

}
