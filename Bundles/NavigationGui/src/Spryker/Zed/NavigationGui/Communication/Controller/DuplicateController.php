<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\NavigationGui\Communication\NavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface getQueryContainer()
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
            $navigationResponseTransfer = $this->getFactory()
                ->getNavigationFacade()
                ->duplicateNavigation($duplicateNavigationForm->getData()->setIdBaseNavigation($idNavigation));

            if ($navigationResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(
                    'Navigation element %d was duplicated successfully.',
                    ['%d' => $idNavigation]
                );

                return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
            }

            foreach ($navigationResponseTransfer->getErrors() as $navigationErrorTransfer) {
                $this->addErrorMessage($navigationErrorTransfer->getMessage());
            }

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        return $this->viewResponse(['duplicateNavigationForm' => $duplicateNavigationForm->createView()]);
    }
}
