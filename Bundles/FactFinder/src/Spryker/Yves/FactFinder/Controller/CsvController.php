<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\FactFinder\Controller;

use Spryker\Yves\Application\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \Spryker\Client\FactFinder\FactFinderClient getClient()
 */
class CsvController extends AbstractController
{

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function productsAction()
    {
        $response = $this->getClient()->getProductCsv($this->getLocale())->getContents();

        return $this->streamedResponse(
            function () use ($response) {
                echo $response;
            },
            200,
            ["Content-type" => "text/csv"]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function categoriesAction()
    {
        $response = $this->getClient()->getCategoryCsv($this->getLocale())->getContents();

        return $this->streamedResponse(
            function () use ($response) {
                echo $response;
            },
            200,
            ["Content-type" => "text/csv"]
        );
    }

    /**
     * @param callable|null $callback
     * @param int $status
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamedResponse($callback = null, $status = 200, $headers = [])
    {
        $streamedResponse = new StreamedResponse($callback, $status, $headers);
        $streamedResponse->send();

        return $streamedResponse;
    }

}
