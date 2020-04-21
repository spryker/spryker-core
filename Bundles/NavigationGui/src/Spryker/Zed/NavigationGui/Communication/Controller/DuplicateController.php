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
class DuplicateController extends AbstractController
{
    public const PARAM_ID_NAVIGATION = 'id-navigation';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $duplicateNavigationForm = $this->getFactory()
            ->createMerchantProfileForm()
            ->handleRequest($request);

        if ($duplicateNavigationForm->isSubmitted() && $duplicateNavigationForm->isValid()) {
            $navigationTransfer = $duplicateNavigationForm->getData();
            $idNavigation = $this->castId($request->query->getInt(self::PARAM_ID_NAVIGATION));
            $baseNavigationTransfer = $this->getFactory()
                ->getNavigationFacade()
                ->findNavigation((new NavigationTransfer())->setIdNavigation($idNavigation));
            $newNavigationTransfer = (new NavigationTransfer())
                ->setName($navigationTransfer->getName())
                ->setKey($navigationTransfer->getKey());
            $this->getFactory()
                ->getNavigationFacade()
                ->duplicateNavigation($newNavigationTransfer, $baseNavigationTransfer);

            $this->addSuccessMessage(
                'Navigation element %d was duplicated successfully.',
                ['%d' => $idNavigation]
            );

            return $this->redirectResponse('/navigation-gui');
        }

        return $this->viewResponse(['duplicateNavigationForm' => $duplicateNavigationForm->createView()]);
    }
}
