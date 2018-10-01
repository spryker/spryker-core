<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\NavigationGui\Communication\NavigationGuiCommunicationFactory getFactory()
 */
class TreeController extends AbstractController
{
    public const PARAM_ID_NAVIGATION = 'id-navigation';
    public const PARAM_NAVIGATION_TREE = 'navigation-tree';

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

        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        $navigationTreeTransfer = $this->getFactory()
            ->getNavigationFacade()
            ->findNavigationTree($navigationTransfer, $localeTransfer);

        return $this->viewResponse([
            'navigationTree' => $navigationTreeTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateHierarchyAction(Request $request)
    {
        $navigationTreeData = $request->request->get(self::PARAM_NAVIGATION_TREE);

        if (!$navigationTreeData) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Incorrect request data.',
            ]);
        }

        $navigationTreeTransfer = new NavigationTreeTransfer();
        $navigationTreeTransfer->fromArray((array)$navigationTreeData);

        $this->getFactory()
            ->getNavigationFacade()
            ->updateNavigationTreeHierarchy($navigationTreeTransfer);

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Navigation tree updated successfully.',
        ]);
    }
}
