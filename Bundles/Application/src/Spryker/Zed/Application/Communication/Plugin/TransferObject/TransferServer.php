<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin\TransferObject;

use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Shared\Library\Communication\Response;
use Spryker\Zed\ZedRequest\Business\Client\Request;
use Spryker\Shared\ZedRequest\Client\ResponseInterface;
use Spryker\Shared\ZedRequest\Client\RequestInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransferServer
{

    /**
     * @var self
     */
    protected static $instance;

    /**
     * @var bool
     */
    protected $repeatIsActive = false;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @var ResponseInterface|Response
     */
    protected $response;

    /**
     * @var LocatorLocatorInterface
     */
    private $locator;

    /**
     * @var Repeater
     */
    private $repeater;

    /**
     * @param Repeater $repeater
     */
    private function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * @param Repeater $repeater
     *
     * @return self
     */
    public static function getInstance(Repeater $repeater = null)
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
     * @return Request
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
     * @return HttpRequest
     */
    private function getHttpRequest()
    {
        if ($this->httpRequest === null) {
            throw new \LogicException('No Http Request found in TransferServer. Maybe you try to access data from it before the request object is injected.');
        }

        return $this->httpRequest;
    }

    /**
     * @param HttpRequest $httpRequest
     *
     * @return self
     */
    public function setRequest(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;

        return $this;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return self
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function send()
    {
        $jsonResponse = new JsonResponse($this->response->toArray(false));
        if ($this->repeatIsActive) {
            $jsonResponse->setEncodingOptions(JSON_PRETTY_PRINT);
        }

        return $jsonResponse;
    }

}
