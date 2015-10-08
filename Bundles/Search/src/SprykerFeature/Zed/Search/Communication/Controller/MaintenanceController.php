<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Search\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Search\Business\SearchFacade;
use SprykerFeature\Zed\Search\Communication\SearchDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SearchFacade getFacade()
 * @method SearchDependencyContainer getDependencyContainer()
 */
class MaintenanceController extends AbstractController
{

    const MESSAGE_RESPONSE = 'Response: %s';
    const URL_SEARCH_MAINTENANCE = '/search/maintenance';

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
     * @return RedirectResponse
     */
    public function dropTimestampsAction()
    {
        $this->getDependencyContainer()->createCollectorFacade()->deleteSearchTimestamps();

        return $this->redirectResponse(self::URL_SEARCH_MAINTENANCE);
    }

    /**
     * @return array
     */
    public function listAction()
    {
        $table = $this->getDependencyContainer()->createSearchTable();

        return $this->viewResponse(['searchTable' => $table->render()]);
    }

    /**
     * @return JsonResponse
     */
    public function listAjaxAction()
    {
        $table = $this->getDependencyContainer()->createSearchTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @return RedirectResponse
     */
    public function deleteAllAction()
    {
        $elasticaResponse = $this->getFacade()->delete();
        $formattedResponse = var_export($elasticaResponse->getData(), true);
        $this->addInfoMessage(sprintf(self::MESSAGE_RESPONSE, $formattedResponse));

        return $this->redirectResponse(self::URL_SEARCH_MAINTENANCE);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function keyAction(Request $request)
    {
        $key = $request->get('key');

        $type = $this->getDependencyContainer()->getConfig()->getElasticaDocumentType();
        $document = $this->getFacade()->getDocument($key, $type);

        return $this->viewResponse([
            'value' => var_export($document->getData(), true),
            'key' => $key,
        ]);
    }

}
