<?php

namespace SprykerFeature\Zed\User\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\User\Persistence\Propel\Map\SpyUserUserTableMap;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UserDependencyContainer getDependencyContainer()
 */
class FormController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function detailsAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getDetailsUserForm($request);
        $form->init();

        if ($form->isValid()) {
            $facade = $this->getFacade();

            $formData = $form->getRequestData();

            $user = new UserTransfer();
            $user->fromArray($formData);
            $user->setIdUserUser($request->query->get('id'));

            $newStatus = ($formData['status'] === true)
                ? SpyUserUserTableMap::COL_STATUS_ACTIVE
                : SpyUserUserTableMap::COL_STATUS_BLOCKED
            ;
            $user->setStatus($newStatus);

            $facade->updateUser($user);
        }

        return $this->jsonResponse($form->renderData());
    }

}
