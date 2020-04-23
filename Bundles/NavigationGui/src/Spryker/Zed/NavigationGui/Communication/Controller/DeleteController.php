<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idNavigation = $this->castId($request->query->getInt(self::PARAM_ID_NAVIGATION));

        return $this->viewResponse([
            'idNavigation' => $idNavigation,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request)
    {
        $idNavigation = $this->castId($request->query->getInt(self::PARAM_ID_NAVIGATION));
        $navigationTransfer = $this->getFactory()
            ->getNavigationFacade()
            ->findNavigation((new NavigationTransfer())->setIdNavigation($idNavigation));
        if (!$navigationTransfer) {
            $this->addErrorMessage('Navigation element %d was not found.', ['%d' => $idNavigation]);

            return $this->redirectResponse('/navigation-gui');
        }

        $this->getFactory()
            ->getNavigationFacade()
            ->deleteNavigation($navigationTransfer);

        $this->addSuccessMessage('Navigation element %d was deleted successfully.', ['%d' => $idNavigation]);

        return $this->redirectResponse('/navigation-gui');
    }
}
