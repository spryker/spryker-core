<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\Request;

use Spryker\Zed\Application\Business\Exception\UrlInvalidException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\Http\Communication\SubRequest\SubRequestHandler} instead.
 */
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
        $subRequest = $this->createSubRequest($request, $url, $additionalSubRequestParameters);
        $subRequestResponse = $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST, true);

        return $subRequestResponse;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $url
     * @param array $additionalSubRequestParameters
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createSubRequest(Request $request, $url, array $additionalSubRequestParameters)
    {
        $subRequest = $this->createRequestObject($request, $url, $additionalSubRequestParameters);
        $subRequest->query->add($request->query->all());
        $subRequest->request->add($request->request->all());

        $urlParts = $this->extractUrlParts($url);
        $this->validateUrlParts($urlParts);
        $this->setRouteAttributes($subRequest, $urlParts);

        return $subRequest;
    }

    /**
     * @param string $url
     *
     * @return string[]
     */
    protected function extractUrlParts($url)
    {
        return explode('/', trim($url, '/'));
    }

    /**
     * @param string[] $urlParts
     *
     * @throws \Spryker\Zed\Application\Business\Exception\UrlInvalidException
     *
     * @return bool
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $url
     * @param array $additionalSubRequestParameters
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createRequestObject(Request $request, $url, array $additionalSubRequestParameters)
    {
        return Request::create(
            $url,
            $request->getMethod(),
            $additionalSubRequestParameters,
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all()
        );
    }
}
