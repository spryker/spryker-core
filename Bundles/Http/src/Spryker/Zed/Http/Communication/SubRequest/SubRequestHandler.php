<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Http\Communication\SubRequest;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function handleSubRequest(Request $request, string $url, array $additionalSubRequestParameters = []): Response
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
    protected function createSubRequest(Request $request, string $url, array $additionalSubRequestParameters): Request
    {
        $subRequest = $this->createRequestObject($request, $url, $additionalSubRequestParameters);
        $subRequest->query->add($request->query->all());
        $subRequest->request->add($request->request->all());

        $urlParts = $this->extractUrlParts($url);
        $this->setRouteAttributes($subRequest, $urlParts);

        return $subRequest;
    }

    /**
     * @param string $url
     *
     * @return string[]
     */
    protected function extractUrlParts(string $url): array
    {
        return explode('/', trim($url, '/'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $subRequest
     * @param string[] $urlParts
     *
     * @return void
     */
    protected function setRouteAttributes(Request $subRequest, array $urlParts): void
    {
        $subRequest->attributes->set('module', $urlParts[0] ?? 'index');
        $subRequest->attributes->set('controller', $urlParts[1] ?? 'index');
        $subRequest->attributes->set('action', $urlParts[2] ?? 'index');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $url
     * @param array $additionalSubRequestParameters
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createRequestObject(Request $request, string $url, array $additionalSubRequestParameters)
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
