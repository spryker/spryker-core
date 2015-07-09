<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRestController
{

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function indexAction(Request $request) {
        $this->request = $request;

        $result = $this->routeRequest();

        return new JsonResponse($result);
    }

    protected function routeRequest() {
        $result = null;
        switch ($this->request->getMethod()) {
            case self::GET :
                $result = $this->get();
                break;
            case self::POST :
                $result = $this->post();
                break;
            case self::PUT :
                $result = $this->put();
                break;
            case self::DELETE :
                $result = $this->delete();
        }

        return $result;
    }

    abstract protected function get();

    abstract protected function post();

    abstract protected function put();

    abstract protected function delete();

}
