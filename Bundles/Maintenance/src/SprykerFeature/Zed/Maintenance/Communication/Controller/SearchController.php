<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Controller;

use Elastica\Exception\ResponseException;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use SprykerFeature\Zed\Maintenance\Communication\MaintenanceDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method MaintenanceFacade getFacade()
 * @method MaintenanceDependencyContainer getDependencyContainer()
 */
class SearchController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $client = $this->getDependencyContainer()->createSearchClient()->getIndexClient();

        try {
            $totalCount = $client->count();
            $mapping = $client->getMapping();
            $metaData = [];

            if (isset($mapping['page']) && isset($mapping['page']['_meta'])) {
                $metaData = $mapping['page']['_meta'];
            }
        } catch (ResponseException $e) {
            $totalCount = 0;
            $metaData = [];
        }

        return $this->viewResponse(
            [
                'totalCount' => $totalCount,
                'metaData' => $metaData,
            ]
        );
    }

    /**
     * @return RedirectResponse
     */
    public function dropTimestampsAction()
    {
        $this->getDependencyContainer()->createCollectorFacade()->deleteSearchTimestamps();

        return $this->redirectResponse('/maintenance/search');
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
        $elasticaResponse = $this->getDependencyContainer()->createSearchClient()->getIndexClient()->delete();
        $this->addInfoMessage('Response: ' . var_export($elasticaResponse->getData(), true));

        return $this->redirectResponse('/maintenance/search');
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function keyAction(Request $request)
    {
        $key = $request->get('key');

        $documentType = $this->getDependencyContainer()->getConfig()->getElasticaDocumentType();

        $document = $this->getDependencyContainer()->createSearchClient()
            ->getIndexClient()->getType($documentType)->getDocument($key);

        return $this->viewResponse([
            'value' => var_export($document->getData(), true),
            'key' => $key,
        ]);
    }

}
