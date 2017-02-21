<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Spryker\Shared\Url\Url;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\NavigationGui\Communication\NavigationGuiCommunicationFactory getFactory()
 */
class NodeController extends AbstractController
{

    const PARAM_ID_NAVIGATION = 'id-navigation';
    const PARAM_ID_NAVIGATION_NODE = 'id-navigation-node';
    const PARAM_SELECTED_TREE_NODE = 'selected-tree-node';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $idNavigation = $this->castId($request->query->getInt(static::PARAM_ID_NAVIGATION));
        $idNavigationNode = $request->query->getInt(static::PARAM_ID_NAVIGATION_NODE);
        $selectedTreeNode = $request->query->get(static::PARAM_SELECTED_TREE_NODE);

        $navigationNodeFormDataProvider = $this->getFactory()
            ->createNavigationNodeFormDataProvider();

        $navigationNodeForm = $this->getFactory()
            ->createNavigationNodeForm(
                $navigationNodeFormDataProvider->getData(),
                $navigationNodeFormDataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($navigationNodeForm->isValid()) {
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
                'Navigation node #%d successfully created.',
                $navigationNodeTransfer->getIdNavigationNode()
            ));

            $queryParams = [
                static::PARAM_ID_NAVIGATION => $idNavigation,
                static::PARAM_ID_NAVIGATION_NODE => $idNavigationNode,
                static::PARAM_SELECTED_TREE_NODE => $idNavigationNode, // TODO: on the root the value of this is incorrect!
            ];

            if ($idNavigationNode) {
                return $this->redirectResponse(Url::generate('/navigation-gui/node/update', $queryParams)->build());
            } else {
                return $this->redirectResponse(Url::generate('/navigation-gui/node/create', $queryParams)->build());
            }
        }

        return $this->viewResponse([
            'idNavigation' => $idNavigation,
            'idNavigationNode' => $idNavigationNode,
            'selectedTreeNode' => $selectedTreeNode,
            'navigationNodeForm' => $navigationNodeForm->createView(),
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
        $selectedTreeNode = $request->query->get(static::PARAM_SELECTED_TREE_NODE);

        $navigationNodeFormDataProvider = $this->getFactory()
            ->createNavigationNodeFormDataProvider();

        $navigationNodeForm = $this->getFactory()
            ->createNavigationNodeForm(
                $navigationNodeFormDataProvider->getData($idNavigationNode),
                $navigationNodeFormDataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($navigationNodeForm->isValid()) {
            /** @var \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer */
            $navigationNodeTransfer = clone $navigationNodeForm->getData();

            $navigationNodeTransfer = $this->getFactory()
                ->getNavigationFacade()
                ->updateNavigationNode($navigationNodeTransfer);

            $this->addSuccessMessage(sprintf(
                'Navigation node #%d successfully updated.',
                $navigationNodeTransfer->getIdNavigationNode()
            ));

            $queryParams = [
                static::PARAM_ID_NAVIGATION => $idNavigation,
                static::PARAM_ID_NAVIGATION_NODE => $idNavigationNode,
                static::PARAM_SELECTED_TREE_NODE => $idNavigationNode,
            ];
            return $this->redirectResponse(Url::generate('/navigation-gui/node/update', $queryParams)->build());
        }

        return $this->viewResponse([
            'idNavigation' => $idNavigation,
            'idNavigationNode' => $idNavigationNode,
            'selectedTreeNode' => $selectedTreeNode,
            'navigationNodeForm' => $navigationNodeForm->createView(),
        ]);
    }

}
