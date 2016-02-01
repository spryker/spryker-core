<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business\Api\Adapter\Http;

use Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Payolution\Business\Exception\ApiHttpRequestException;

abstract class AbstractHttpAdapter implements AdapterInterface
{

    /**
     * @var static string[]
     */
    public static $requestContentTypes = [
        'FORM' => 'application/x-www-form-urlencoded;charset=UTF-8',
        'XML' => 'text/xml;charset=UTF-8',
    ];

    /**
     * @var string
     */
    protected $gatewayUrl;

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @param string $gatewayUrl
     * @param string $contentType
     */
    public function __construct($gatewayUrl, $contentType)
    {
        $this->gatewayUrl = $gatewayUrl;
        $this->contentType = $contentType;
    }

    /**
     * @param array|string $data
     *
     * @return string
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
     * @return string
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
     * @throws \Spryker\Zed\Payolution\Business\Exception\ApiHttpRequestException
     *
     * @return string
     */
    abstract protected function send($request);

}
