<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Search\Business\SearchFacadeInterface getFacade()
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 */
class MaintenanceController extends AbstractController
{
    public const MESSAGE_RESPONSE = 'Response: %s';
    public const URL_SEARCH_MAINTENANCE = '/search/maintenance';

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([
            'totalCount' => $this->getFacade()->getTotalCount(),
            'metaData' => $this->getFacade()->getMetaData(),
        ]);
    }

    /**
     * @return array
     */
    public function listAction()
    {
        $table = $this->getFactory()->createSearchTable();

        return $this->viewResponse(['searchTable' => $table->render()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listAjaxAction()
    {
        $table = $this->getFactory()->createSearchTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAllAction()
    {
        $elasticaResponse = $this->getFacade()->delete();
        $formattedResponse = var_export($elasticaResponse->getData(), true);
        $this->addInfoMessage(self::MESSAGE_RESPONSE, ['%s' => $formattedResponse]);

        return $this->redirectResponse(self::URL_SEARCH_MAINTENANCE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function keyAction(Request $request)
    {
        $key = $request->get('key');

        $type = $this->getFactory()->getElasticaDocumentType();
        $document = $this->getFacade()->getDocument($key, $type);

        return $this->viewResponse([
            'value' => var_export($document->getData(), true),
            'key' => $key,
        ]);
    }
}
