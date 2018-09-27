<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\NavigationGui\Communication\NavigationGuiCommunicationFactory getFactory()
 */
class NodeController extends AbstractController
{
    public const PARAM_ID_NAVIGATION = 'id-navigation';
    public const PARAM_ID_NAVIGATION_NODE = 'id-navigation-node';
    public const PARAM_ID_SELECTED_TREE_NODE = 'id-selected-tree-node';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $idNavigation = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION));
        $idNavigationNode = $request->query->getInt(static::PARAM_ID_NAVIGATION_NODE);
        $idSelectedTreeNode = $request->query->get(static::PARAM_ID_SELECTED_TREE_NODE);

        $navigationNodeFormDataProvider = $this->getFactory()
            ->createNavigationNodeFormDataProvider();

        $navigationNodeForm = $this->getFactory()
            ->getNavigationNodeForm(
                $navigationNodeFormDataProvider->getData(),
                $navigationNodeFormDataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($navigationNodeForm->isSubmitted() && $navigationNodeForm->isValid()) {
            /** @var \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer */
            $navigationNodeTransfer = $navigationNodeForm->getData();

            $navigationNodeTransfer->setFkNavigation($idNavigation);
            if ($idNavigationNode) {
                $navigationNodeTransfer->setFkParentNavigationNode($idNavigationNode);
            }

            $navigationNodeTransfer = $this->getFactory()
                ->getNavigationFacade()
                ->createNavigationNode($navigationNodeTransfer);

            $this->addSuccessMessage(sprintf(
                'Navigation node "%s" was created successfully.',
                $navigationNodeTransfer->getNavigationNodeLocalizedAttributes()->getArrayCopy()[0]->getTitle()
            ));

            $queryParams = [
                static::PARAM_ID_NAVIGATION => $idNavigation,
                static::PARAM_ID_NAVIGATION_NODE => $idNavigationNode,
                static::PARAM_ID_SELECTED_TREE_NODE => $idNavigationNode,
            ];

            if ($idNavigationNode) {
                return $this->redirectResponse(Url::generate('/navigation-gui/node/update', $queryParams)->build());
            }

            return $this->redirectResponse(Url::generate('/navigation-gui/node/create', $queryParams)->build());
        }

        return $this->viewResponse([
            'idNavigation' => $idNavigation,
            'idNavigationNode' => $idNavigationNode,
            'idSelectedTreeNode' => $idSelectedTreeNode,
            'navigationNodeForm' => $navigationNodeForm->createView(),
            'localeCollection' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $idNavigation = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION));
        $idNavigationNode = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION_NODE));
        $idSelectedTreeNode = $request->query->get(static::PARAM_ID_SELECTED_TREE_NODE);

        $navigationNodeFormDataProvider = $this->getFactory()
            ->createNavigationNodeFormDataProvider();

        $navigationNodeForm = $this->getFactory()
            ->getNavigationNodeForm(
                $navigationNodeFormDataProvider->getData($idNavigationNode),
                $navigationNodeFormDataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($navigationNodeForm->isSubmitted() && $navigationNodeForm->isValid()) {
            /** @var \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer */
            $navigationNodeTransfer = clone $navigationNodeForm->getData();

            $navigationNodeTransfer = $this->getFactory()
                ->getNavigationFacade()
                ->updateNavigationNode($navigationNodeTransfer);

            $this->addSuccessMessage(sprintf(
                'Navigation node "%s" was updated successfully.',
                $navigationNodeTransfer->getNavigationNodeLocalizedAttributes()->getArrayCopy()[0]->getTitle()
            ));

            $queryParams = [
                static::PARAM_ID_NAVIGATION => $idNavigation,
                static::PARAM_ID_NAVIGATION_NODE => $idNavigationNode,
                static::PARAM_ID_SELECTED_TREE_NODE => $idNavigationNode,
            ];
            return $this->redirectResponse(Url::generate('/navigation-gui/node/update', $queryParams)->build());
        }

        return $this->viewResponse([
            'idNavigation' => $idNavigation,
            'idNavigationNode' => $idNavigationNode,
            'idSelectedTreeNode' => $idSelectedTreeNode,
            'navigationNodeForm' => $navigationNodeForm->createView(),
            'localeCollection' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idNavigation = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION));
        $idNavigationNode = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION_NODE));

        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer->setIdNavigationNode($idNavigationNode);
        $navigationNodeTransfer = $this->getFactory()
            ->getNavigationFacade()
            ->findNavigationNode($navigationNodeTransfer);

        $this->getFactory()
            ->getNavigationFacade()
            ->deleteNavigationNode($navigationNodeTransfer);

        $this->addSuccessMessage(sprintf(
            'Navigation node "%s" was deleted successfully.',
            $navigationNodeTransfer->getNavigationNodeLocalizedAttributes()->getArrayCopy()[0]->getTitle()
        ));

        $queryParams = [
            static::PARAM_ID_NAVIGATION => $idNavigation,
            static::PARAM_ID_NAVIGATION_NODE => null,
            static::PARAM_ID_SELECTED_TREE_NODE => 0,
        ];

        return $this->redirectResponse(Url::generate('/navigation-gui/node/create', $queryParams)->build());
    }
}
