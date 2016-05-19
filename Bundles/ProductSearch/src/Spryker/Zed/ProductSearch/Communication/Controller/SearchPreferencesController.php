<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductSearch\Communication\ProductSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacade getFacade()
 */
class SearchPreferencesController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createSearchPreferencesTable();

        return $this->viewResponse([
            'filtersTable' => $table->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createSearchPreferencesTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * 
     * @return array
     */
    public function editAction(Request $request)
    {
        $form = $this->getFactory()
            ->createSearchPreferencesForm()
            ->handleRequest($request);

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

}
