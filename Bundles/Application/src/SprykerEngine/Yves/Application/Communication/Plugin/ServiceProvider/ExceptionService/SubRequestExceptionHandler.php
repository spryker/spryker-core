<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider\ExceptionService;

use SprykerEngine\Shared\Application\Communication\Application;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param Application $application
     * @param string $errorPageNamePrefix
     */
    public function __construct(Application $application, $errorPageNamePrefix = self::DEFAULT_URL_NAME_PREFIX)
    {
        $this->application = $application;
        $this->errorPageNamePrefix = $errorPageNamePrefix;
    }

    /**
     * @param FlattenException $exception
     *
     * @return Response
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
