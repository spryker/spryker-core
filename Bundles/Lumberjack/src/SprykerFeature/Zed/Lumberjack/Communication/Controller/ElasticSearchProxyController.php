<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Lumberjack\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Lumberjack\Business\Model\ElasticSearch\Proxy;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class ElasticSearchProxyController extends AbstractController
{

    const CSV_DOWNLOAD_CONTROLLER = '/lumberjack/elastic-search-proxy/download';

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchAction(Request $request)
    {
        $this->disableRendering();
        $response = json_encode(
            json_decode(
                $this->facadeLumberjack->getSearch(
                    $request->query->getIterator()->getArrayCopy(),
                    $request->attributes->getIterator()->getArrayCopy(),
                    Proxy::SEARCH_TYPE_SINGLE
                )
            )
        );

        $responseObject = json_decode($response);
        $queryString = $request->attributes->get('request');
        $responseObject->last_query = $queryString;
        $responseObject->download_url = self::CSV_DOWNLOAD_CONTROLLER . '?query=' . urlencode($queryString)
            . '&hits=' . $responseObject->hits->total;

        return $this->jsonResponse($responseObject);
    }

    /**
     * @param Request $request
     */
    protected function downloadAction(Request $request)
    {
        $this->disableRendering();
        $response = json_encode(
            json_decode(
                $this->facadeLumberjack->getSearch(
                    [
                        'size' => $request->query->get('hits'),
                        'from' => 0,
                    ],
                    [
                        'request' => $request->query->get('query'),
                    ],
                    Proxy::SEARCH_TYPE_SINGLE
                )
            )
        );

        $csvContent = $this->facadeLumberjack->getCsvFromElasticSearchJsonResponse($response, ';', '"');
        header('Content-type: text/csv');
        header('Cache-Control: no-store, no-cache');
        header('Content-Disposition: attachment; filename="lumberjack.csv"');
        echo $csvContent;
    }

    /**
     * Converts an Exception into an simple, json-encoded object
     *
     * @param \Exception $e
     *
     * @return string
     */
    protected function exceptionToJson(\Exception $e)
    {
        $json = new \StdClass();
        $json->message = $e->getMessage();
        $json->code = $e->getCode();
        $json->severity = $e->getSeverity();
        $json->file = $e->getFile();
        $json->line = $e->getLine();

        return json_encode($json);
    }

    /**
     * Converts an ErrorException into an simple, json-encoded object
     *
     * @param \ErrorException $e
     *
     * @return string
     */
    protected function errorExceptionToJson(\ErrorException $e)
    {
        $json = new \StdClass();
        $json->message = $e->getMessage();
        $json->code = $e->getCode();
        $json->severity = $e->getSeverity();
        $json->file = $e->getFile();
        $json->line = $e->getLine();

        return json_encode($json);
    }

    /**
     * TODO remove this
     */
    protected function disableRendering()
    {
        header('Access-Control-Allow-Origin: *');
    }

}
