<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * @method \Spryker\Zed\NavigationGui\Communication\NavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface getQueryContainer()
 */
class DeleteController extends AbstractController
{
    public const PARAM_ID_NAVIGATION = 'id-navigation';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_DELETE)) {
            throw new MethodNotAllowedHttpException([Request::METHOD_DELETE], 'This action requires a DELETE request.');
        }

        $idNavigation = $this->castId($request->query->getInt(self::PARAM_ID_NAVIGATION));

        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($idNavigation);
        $navigationTransfer = $this->getFactory()
            ->getNavigationFacade()
            ->findNavigation($navigationTransfer);

        if ($navigationTransfer) {
            $this->getFactory()
                ->getNavigationFacade()
                ->deleteNavigation($navigationTransfer);

            $this->addSuccessMessage('Navigation element %d was deleted successfully.', ['%d' => $idNavigation]);
        } else {
            $this->addErrorMessage('Navigation element %d was not found.', ['%d' => $idNavigation]);
        }

        return $this->redirectResponse('/navigation-gui');
    }
}
