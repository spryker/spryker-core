<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Generated\Shared\Transfer\DuplicateNavigationTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\NavigationGui\Communication\NavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiRepositoryInterface getRepository()
 */
class DuplicateController extends AbstractController
{
    public const PARAM_ID_NAVIGATION = 'id-navigation';

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
        $duplicateNavigationForm = $this->getFactory()
            ->createDuplicateNavigationForm()
            ->handleRequest($request);

        return $this->handleDuplicateNavigationForm($duplicateNavigationForm, $request);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $duplicateNavigationForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleDuplicateNavigationForm(FormInterface $duplicateNavigationForm, Request $request)
    {
        if ($duplicateNavigationForm->isSubmitted() && $duplicateNavigationForm->isValid()) {
            $idNavigation = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION));
            $duplicateNavigationTransfer = $this->createDuplicateNavigationTransfer(
                $duplicateNavigationForm->getData(),
                $idNavigation
            );
            $navigationResponseTransfer = $this->getFactory()
                ->getNavigationFacade()
                ->duplicateNavigation($duplicateNavigationTransfer);

            if (!$navigationResponseTransfer->getIsSuccessful()) {
                $this->addErrorMessage($navigationResponseTransfer->getError()->getMessage());

                return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
            }

            $this->addSuccessMessage(
                'Navigation element %d was duplicated successfully.',
                ['%d' => $idNavigation]
            );

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        return $this->viewResponse(['duplicateNavigationForm' => $duplicateNavigationForm->createView()]);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     * @param int $idBaseNavigation
     *
     * @return \Generated\Shared\Transfer\DuplicateNavigationTransfer
     */
    protected function createDuplicateNavigationTransfer(NavigationTransfer $navigationTransfer, int $idBaseNavigation): DuplicateNavigationTransfer
    {
        return (new DuplicateNavigationTransfer())
            ->setKey($navigationTransfer->getKey())
            ->setName($navigationTransfer->getName())
            ->setIdBaseNavigation($idBaseNavigation);
    }
}
