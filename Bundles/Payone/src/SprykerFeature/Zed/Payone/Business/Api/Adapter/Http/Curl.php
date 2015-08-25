<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Adapter\Http;

use SprykerFeature\Zed\Payone\Business\Exception\TimeoutException;

class Curl extends AbstractHttpAdapter
{

    /**
     * @throws \ErrorException
     *
     * @return array
     */
    protected function performRequest(array $params)
    {
        $response = [];
        $urlArray = $this->generateUrlArray($params);

        $urlHost = $urlArray['host'];
        $urlPath = isset($urlArray['path']) ? $urlArray['path'] : '';
        $urlScheme = $urlArray['scheme'];
        $urlQuery = $urlArray['query'];

        $url = $urlScheme . '://' . $urlHost . $urlPath;

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $urlQuery);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->getTimeout());

        $result = curl_exec($curl);

        $this->setRawResponse($result);

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            if (curl_errno($curl) === CURLE_OPERATION_TIMEOUTED) {
                throw new TimeoutException('Timeout - Payone Communication');
            }
            throw new \ErrorException('Invalid Response - Payone Communication: ' . curl_errno($curl));
        }
        elseif (curl_error($curl)) {
            $response[] = 'errormessage=' . curl_errno($curl) . ': ' . curl_error($curl);
        }
        else {
            $response = explode("\n", $result);
        }
        curl_close($curl);

        return $response;
    }

}
