<?php

namespace SprykerFeature\Zed\Application\Communication\Plugin\TransferObject;

use SprykerFeature\Shared\Library\Communication\Response;
use SprykerFeature\Zed\ZedRequest\Business\Client\Request;
use SprykerFeature\Shared\ZedRequest\Client\ResponseInterface;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;

use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransferServer
{

    /**
     * @var TransferServer
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

    private function __construct()
    {
    }

    /**
     * @return $this
     * @static
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

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
                    Locator::getInstance(),
                    Repeater::getRepeatData($this->getHttpRequest()->query->get('mvc'))['params']
                );
            } else {
                $transferValues = json_decode($this->getHttpRequest()->getContent(), true);
                $this->request = new Request(Locator::getInstance(), $transferValues);
                Repeater::setRepeatData($this->request, $this->httpRequest);
            }
        }

        return $this->request;
    }

    /**
     * @return HttpRequest
     */
    private function getHttpRequest()
    {
        if (is_null($this->httpRequest)) {
            throw new \LogicException('No Http Request found in TransferServer. Maybe you try to access data from it before the request object is injected.');
        }

        return $this->httpRequest;
    }

    /**
     * @param HttpRequest $httpRequest
     * @return $this
     */
    public function setRequest(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;

        return $this;
    }

    /**
     * @param ResponseInterface $response
     * @return $this
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
        return new JsonResponse($this->response->toArray(false));
    }
}
