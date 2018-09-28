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
 */
class ToggleStatusController extends AbstractController
{
    public const PARAM_ID_NAVIGATION = 'id-navigation';

    public const MESSAGE_MAP_UPDATE_SUCCESS = [
        true => 'Navigation element %d was activated successfully.',
        false => 'Navigation element %d was deactivated successfully.',
    ];

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idNavigation = $this->castId($request->query->getInt(self::PARAM_ID_NAVIGATION));

        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($idNavigation);
        $navigationTransfer = $this->getFactory()
            ->getNavigationFacade()
            ->findNavigation($navigationTransfer);

        if ($navigationTransfer) {
            $navigationTransfer->setIsActive(!$navigationTransfer->getIsActive());

            $this->getFactory()
                ->getNavigationFacade()
                ->updateNavigation($navigationTransfer);

            $this->addSuccessMessage(
                sprintf(static::MESSAGE_MAP_UPDATE_SUCCESS[$navigationTransfer->getIsActive()], $idNavigation)
            );
        } else {
            $this->addErrorMessage(sprintf('Navigation element %d was not found.', $idNavigation));
        }

        return $this->redirectResponse('/navigation-gui');
    }
}
