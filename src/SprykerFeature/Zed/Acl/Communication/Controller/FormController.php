<?php

namespace SprykerFeature\Zed\Acl\Communication\Controller;

use SprykerFeature\Zed\Acl\Communication\AclDependencyContainer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AclDependencyContainer getDependencyContainer()
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
        $form = $this->getDependencyContainer()->createUserWithGroupForm(
            $request
        );

        $idUser = $request->get('id');

        if (false === empty($idUser)) {
            $form->setUserId($idUser);
        }

        $form->init();

        if ($form->isValid()) {
            $data = $form->getRequestData();

            $userGroup = false;

            $user = new \Generated\Shared\Transfer\UserUserTransfer();
            $user->setFirstName($data['first_name'])
                ->setLastName($data['last_name'])
                ->setUsername($data['username'])
                ->setPassword($data['password']);

            if (false === empty($idUser)) {
                $user->setIdUserUser($data['id_user_user']);
                $user = $this->getLocator()
                    ->user()
                    ->facade()
                    ->updateUser($user);

                $userGroup = $this->getLocator()
                    ->acl()
                    ->facade()
                    ->getUserGroup($idUser);

                if ($userGroup->getIdAclGroup() !== $data['id_acl_group']) {
                    $this->getLocator()
                        ->acl()
                        ->facade()
                        ->removeUserFromGroup($idUser, $userGroup->getIdAclGroup());
                }
            } else {
                $user = $this->getLocator()
                    ->user()
                    ->facade()
                    ->addUser(
                        $user->getFirstName(),
                        $user->getLastName(),
                        $user->getUsername(),
                        $user->getPassword()
                    );
            }

            if ($userGroup === false || $userGroup->getIdAclGroup() !== $data['id_acl_group']) {
                $this->getLocator()->acl()->facade()->addUserToGroup($user->getIdUserUser(), $data['id_acl_group']);
            }
        }

        return $this->jsonResponse($form->renderData());
    }
}
