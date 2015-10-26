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

    /**
     * @var Application
     */
    protected $application;

    /**
     * DefaultExceptionController constructor.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param FlattenException $exception
     *
     * @return Response
     */
    public function handleException(FlattenException $exception)
    {
        $request = Request::create('/error/' . $exception->getStatusCode(), 'GET', [
            'exception' => $exception,
        ]);

        $response = $this->application->handle($request, HttpKernelInterface::SUB_REQUEST, false);

        return $response;
    }

}
