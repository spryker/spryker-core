<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRestController
{

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $this->request = $request;

        $result = $this->routeRequest();

        return new JsonResponse($result);
    }

    /**
     * @return mixed|null
     */
    protected function routeRequest()
    {
        $result = null;
        switch ($this->request->getMethod()) {
            case self::GET:
                $result = $this->get();
                break;
            case self::POST:
                $result = $this->post();
                break;
            case self::PUT:
                $result = $this->put();
                break;
            case self::DELETE:
                $result = $this->delete();
        }

        return $result;
    }

    /**
     * @return mixed
     */
    abstract protected function get();

    /**
     * @return mixed
     */
    abstract protected function post();

    /**
     * @return mixed
     */
    abstract protected function put();

    /**
     * @return mixed
     */
    abstract protected function delete();

}
