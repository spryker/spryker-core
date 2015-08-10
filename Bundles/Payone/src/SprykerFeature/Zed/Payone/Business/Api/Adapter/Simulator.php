<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Adapter;

use SprykerFeature\Zed\Payone\Business\Api\Adapter\Http\AbstractHttpAdapter;

class Simulator extends AbstractHttpAdapter
{

    /**
     * @var array
     */
    protected $rawResponse;

    /**
     * @param array $rawResponse
     */
    public function setRawResponseAsArray(array $rawResponse)
    {
        $this->rawResponse = $this->createRawResponseFromArray($rawResponse);
    }

    /**
     * @param string $rawResponse
     */
    public function setRawResponseAsString($rawResponse)
    {
        $this->rawResponse = $rawResponse;
    }

    /**
     * @param array $request
     *
     * @return string
     */
    protected function createRawResponseFromArray(array $request)
    {
        $rawResponse = '';
        $arrayCount = count($request);
        $count = 1;
        foreach ($request as $key => $value) {
            $rawResponse .= $key . '=' . $value;
            if ($count < $arrayCount) {
                $rawResponse .= "\n";
            }
            $count++;
        }

        return $rawResponse;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function performRequest(array $params)
    {
        $this->setRawResponse($this->rawResponse);
        $response = explode("\n", $this->rawResponse);

        return $response;
    }

    /**
     * @return string
     */
    public function getUrl()
    {

    }

}
