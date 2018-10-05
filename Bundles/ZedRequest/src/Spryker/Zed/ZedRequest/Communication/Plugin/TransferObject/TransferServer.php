<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Communication\Plugin\TransferObject;

use LogicException;
use Spryker\Shared\ZedRequest\Client\ResponseInterface;
use Spryker\Zed\ZedRequest\Business\Client\Request;
use Spryker\Zed\ZedRequest\Business\Model\Repeater;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;

class TransferServer
{
    /**
     * @var self|null
     */
    protected static $instance;

    /**
     * @var bool
     */
    protected $repeatIsActive = false;

    /**
     * @var \Spryker\Zed\ZedRequest\Business\Client\Request|null
     */
    private $request;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $httpRequest;

    /**
     * @var \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    protected $response;

    /**
     * @var \Spryker\Zed\ZedRequest\Business\Model\Repeater
     */
    private $repeater;

    /**
     * @param \Spryker\Zed\ZedRequest\Business\Model\Repeater $repeater
     */
    private function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * @param \Spryker\Zed\ZedRequest\Business\Model\Repeater|null $repeater
     *
     * @return static
     */
    public static function getInstance(?Repeater $repeater = null)
    {
        if (self::$instance) {
            return self::$instance;
        }

        if ($repeater === null) {
            $repeater = new Repeater();
        }

        self::$instance = new static($repeater);

        return self::$instance;
    }

    /**
     * @return void
     */
    public function activateRepeating()
    {
        $this->repeatIsActive = true;
    }

    /**
     * @return \Spryker\Zed\ZedRequest\Business\Client\Request
     */
    public function getRequest()
    {
        if (!$this->request) {
            if ($this->repeatIsActive) {
                $this->request = new Request(
                    $this->repeater->getRepeatData($this->getHttpRequest()->query->get('mvc'))['params']
                );
            } else {
                $transferValues = json_decode($this->getHttpRequest()->getContent(), true);
                $this->request = new Request($transferValues);
                $this->repeater->setRepeatData($this->request, $this->httpRequest);
            }
        }

        return $this->request;
    }

    /**
     * @throws \LogicException
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getHttpRequest()
    {
        if ($this->httpRequest === null) {
            throw new LogicException('No Http Request found in TransferServer. Maybe you try to access data from it before the request object is injected.');
        }

        return $this->httpRequest;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return $this
     */
    public function setRequest(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;

        return $this;
    }

    /**
     * @param \Spryker\Shared\ZedRequest\Client\ResponseInterface $response
     *
     * @return $this
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function send()
    {
        $jsonResponse = new JsonResponse($this->response->toArray(), Response::HTTP_OK, ['X-Zed-Host' => 1]);
        if ($this->repeatIsActive) {
            $jsonResponse->setEncodingOptions(JSON_PRETTY_PRINT);
        }

        return $jsonResponse;
    }
}
