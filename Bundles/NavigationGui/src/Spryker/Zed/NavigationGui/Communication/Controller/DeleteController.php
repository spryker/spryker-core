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

    protected const MESSAGE_NAVIGATION_REMOVAL_SUCCESS = 'Navigation element %d was deleted successfully.';
    protected const MESSAGE_NAVIGATION_REMOVAL_FAIL = 'Navigation element %d was not found.';

    /**
     * @uses \Spryker\Zed\NavigationGui\NavigationGuiConfig::REDIRECT_URL_DEFAULT
     */
    protected const REDIRECT_URL_DEFAULT = '/navigation-gui';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idNavigation = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION));

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
        $idNavigation = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION));
        $navigationTransfer = $this->getFactory()
            ->getNavigationFacade()
            ->findNavigation((new NavigationTransfer())->setIdNavigation($idNavigation));
        if (!$navigationTransfer) {
            $this->addErrorMessage(static::MESSAGE_NAVIGATION_REMOVAL_FAIL, ['%d' => $idNavigation]);

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        $this->getFactory()
            ->getNavigationFacade()
            ->deleteNavigation($navigationTransfer);

        $this->addSuccessMessage(static::MESSAGE_NAVIGATION_REMOVAL_SUCCESS, ['%d' => $idNavigation]);

        return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
    }
}
