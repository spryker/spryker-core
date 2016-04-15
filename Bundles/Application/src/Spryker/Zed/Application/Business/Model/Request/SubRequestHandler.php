<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\Request;

use Spryker\Zed\Application\Business\Exception\UrlInvalidException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class SubRequestHandler implements SubRequestHandlerInterface
{

    /**
     * @var \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected $httpKernel;

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface $httpKernel
     */
    public function __construct(HttpKernelInterface $httpKernel)
    {
        $this->httpKernel = $httpKernel;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $url
     * @param array $additionalSubRequestParameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleSubRequest(Request $request, $url, array $additionalSubRequestParameters = [])
    {
        $urlParts = $this->extractUrlParts($url);

        $this->validateUrlParts($urlParts);

        $subRequestParameters = $this->mergeRequestArguments($request, $additionalSubRequestParameters);
        $subRequest = $this->createSubRequest($request, $url, $subRequestParameters);

        $this->setRouteAttributes($subRequest, $urlParts);

        return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST, true);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     * @return array
     */
    protected function mergeRequestArguments(Request $request, array $parameters)
    {
        $subRequestParameters = array_merge(
            $parameters,
            $request->query->all(),
            $request->attributes->all()
        );

        if ($request->getMethod() === Request::METHOD_POST) {
            $subRequestParameters = array_merge($subRequestParameters, $request->request->all());
        }

        return $subRequestParameters;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $url
     * @param array $subRequestParameters
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createSubRequest(Request $request, $url, array $subRequestParameters)
    {
        $subRequest = Request::create(
            $url,
            $request->getMethod(),
            $subRequestParameters,
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all()
        );

        return $subRequest;
    }

    /**
     * @param string $url
     * @return string[]
     */
    protected function extractUrlParts($url)
    {
        return explode('/', trim($url, '/'));
    }

    /**
     * @param string[] $urlParts
     * @return bool
     * @throws \Spryker\Zed\Application\Business\Exception\UrlInvalidException
     */
    protected function validateUrlParts(array $urlParts)
    {
        if (empty($urlParts[0]) || empty($urlParts[1]) || empty($urlParts[2])) {
            throw new UrlInvalidException('Invalid subrequest url');
        }

        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $subRequest
     * @param string[] $urlParts
     *
     * @return void
     */
    protected function setRouteAttributes(Request $subRequest, array $urlParts)
    {
        $subRequest->attributes->set('module', $urlParts[0]);
        $subRequest->attributes->set('controller', $urlParts[1]);
        $subRequest->attributes->set('action', $urlParts[2]);
    }

}
