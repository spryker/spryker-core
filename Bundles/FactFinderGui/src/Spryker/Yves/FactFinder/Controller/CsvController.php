<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;use Symfony\Component\HttpFoundation\StreamedResponse;
use \Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\FactFinder\FactFinderClient getClient()
 */
class CsvController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function productsAction(Request $request)
    {
        $locale = $request->get('locale', $this->getLocale());
        $number = $request->get('number', '');
        $response = $this->getClient()
            ->getProductCsv($locale, $number)
            ->getContents();

        return $this->streamedResponse(
            function () use ($response) {
                echo $response;
            },
            200,
            ["Content-type" => "text/csv"]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function categoriesAction(Request $request)
    {
        $locale = $request->get('locale', $this->getLocale());
        $response = $this->getClient()
            ->getCategoryCsv($locale)
            ->getContents();

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
