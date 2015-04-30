<?php

namespace SprykerFeature\Zed\Acl\Communication\Controller;

use SprykerFeature\Zed\Acl\Communication\Form\UserForm;
use SprykerFeature\Zed\Acl\Communication\AclDependencyContainer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AclDependencyContainer getDependencyContainer()
 */
class FormControllerProposal extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function formAction(Request $request)
    {
        $form = $this->getForm($request);

        $idUser = $request->get('id');
        if (false === empty($idUser)) {
            //the form can load the default data based on the given user
            $form->setUserId($idUser);
        }

        $form->init();

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->getForm($request);
        $form->init();

        if (false === $form->isValid()) {
            $this->jsonResponse($form->renderData(), 400);
        }

        $data = $form->getRequestData();

        $user = $this->getLocator()
            ->user()
            ->facade()
            ->addUser(
                $data['first_name'],
                $data['last_name'],
                $data['username'],
                $data['password']
            );

        $this->getLocator()->acl()->facade()->addUserToGroup($user->getIdUserUser(), $data['id_acl_group']);

        return $this->jsonResponse($form->renderData(), 201);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request)
    {
        $form = $this->getForm($request);
        $form->init();

        if (false === $form->isValid()) {
            $this->jsonResponse($form->renderData(), 400);
        }

        $data = $form->getRequestData();

        $user = new \Generated\Shared\Transfer\UserUserTransfer();
        $user->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setUsername($data['username'])
            ->setPassword($data['password'])
            ->setIdUserUser($data['id_user_user']);

        $user = $this->getLocator()
            ->user()
            ->facade()
            ->updateUser($user);

        $userGroup = $this->getLocator()
            ->acl()
            ->facade()
            ->getUserGroup($user->getIdUserUser());

        if ($userGroup->getIdAclGroup() !== $data['id_acl_group']) {
            $this->getLocator()
                ->acl()
                ->facade()
                ->removeUserFromGroup($data['id_user_user'], $userGroup->getIdAclGroup());

            $this->getLocator()->acl()->facade()->addUserToGroup($user->getIdUserUser(), $data['id_acl_group']);
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $idUser = $request->get('id');

        if (false === $this->getLocator()->user()->facade()->removeUser($idUser)) {
            $this->jsonResponse(["some error"], 400);
        }

        return $this->jsonResponse(["success message"]);
    }

    /**
     * @param Request $request
     *
     * @return UserForm
     */
    protected function getForm(Request $request)
    {
        return $this->getDependencyContainer()->createUserWithGroupForm($request);
    }
}
