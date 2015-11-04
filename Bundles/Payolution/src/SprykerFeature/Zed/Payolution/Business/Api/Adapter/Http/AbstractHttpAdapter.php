<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Exception\ApiHttpRequestException;

abstract class AbstractHttpAdapter implements AdapterInterface
{

    static $request_content_types = array(
        'form' => 'application/x-www-form-urlencoded;charset=UTF-8',
        'xml' => 'text/xml;charset=UTF-8'
    );

    /**
     * @var string
     */
    protected $gatewayUrl;

    /**
     * @param string $gatewayUrl
     */
    public function __construct($gatewayUrl)
    {
        $this->gatewayUrl = $gatewayUrl;
    }

    /**
     * @param array|string $data
     *
     * @return array
     */
    public function sendRequest($data)
    {
        $request = $this->buildRequest($data);
        return $this->send($request);
    }

    /**
     * @param array|string $data
     * @param string $user
     * @param string $password
     *
     * @return array
     */
    public function sendAuthorizedRequest($data, $user, $password)
    {
        $request = $this->buildRequest($data);
        $this->authorizeRequest($request, $user, $password);
        return $this->send($request);
    }

    /**
     * @param array|string $data
     *
     * @return object
     */
    abstract protected function buildRequest($data);

    /**
     * @param object $request
     * @param string $user
     * @param string $password
     *
     * @return void
     */
    abstract protected function authorizeRequest($request, $user, $password);

    /**
     * @param object $request
     *
     * @throws ApiHttpRequestException
     *
     * @return array
     */
    abstract protected function send($request);
}
