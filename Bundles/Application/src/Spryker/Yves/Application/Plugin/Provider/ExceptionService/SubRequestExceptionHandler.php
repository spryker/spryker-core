<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Application\Plugin\Provider\ExceptionService;

use Spryker\Shared\Application\Communication\Application;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class SubRequestExceptionHandler implements ExceptionHandlerInterface
{

    const DEFAULT_URL_NAME_PREFIX = 'error/';

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var string
     */
    protected $errorPageNamePrefix;

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     * @param string $errorPageNamePrefix
     */
    public function __construct(Application $application, $errorPageNamePrefix = self::DEFAULT_URL_NAME_PREFIX)
    {
        $this->application = $application;
        $this->errorPageNamePrefix = $errorPageNamePrefix;
    }

    /**
     * @param \Symfony\Component\Debug\Exception\FlattenException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleException(FlattenException $exception)
    {
        $errorPageUrl = $this->application->url($this->errorPageNamePrefix . $exception->getStatusCode());
        $request = Request::create($errorPageUrl, 'GET', [
            'exception' => $exception,
        ]);

        $response = $this->application->handle($request, HttpKernelInterface::SUB_REQUEST, false);

        return $response;
    }

}
