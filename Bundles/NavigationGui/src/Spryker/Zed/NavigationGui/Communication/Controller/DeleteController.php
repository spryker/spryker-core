<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Generated\Shared\Transfer\NavigationCriteriaTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    protected const MESSAGE_PARAM = '%d';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idNavigation = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION));

        $deleteNavigationForm = $this->getFactory()->createDeleteNavigationForm();
        $deleteNavigationForm->handleRequest($request);

        $navigationCriteriaTransfer = new NavigationCriteriaTransfer();
        $navigationCriteriaTransfer->setIdNavigation($idNavigation);
        $navigationTransfer = $this->getFactory()
            ->getNavigationFacade()
            ->findNavigationByCriteria($navigationCriteriaTransfer);
        if (!$navigationTransfer) {
            $this->addErrorMessage(static::MESSAGE_NAVIGATION_REMOVAL_FAIL, [static::MESSAGE_PARAM => $idNavigation]);

            return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultRedirectUrl());
        }

        if ($deleteNavigationForm->isSubmitted() && $deleteNavigationForm->isValid()) {
            return $this->handleSubmitForm($navigationTransfer);
        }

        return $this->viewResponse([
            'idNavigation' => $idNavigation,
            'deleteNavigationForm' => $deleteNavigationForm->createView(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubmitForm(NavigationTransfer $navigationTransfer): RedirectResponse
    {
        $this->getFactory()->getNavigationFacade()->deleteNavigation($navigationTransfer);
        $this->addSuccessMessage(
            static::MESSAGE_NAVIGATION_REMOVAL_SUCCESS,
            [static::MESSAGE_PARAM => $navigationTransfer->getIdNavigation()]
        );

        return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultRedirectUrl());
    }
}
