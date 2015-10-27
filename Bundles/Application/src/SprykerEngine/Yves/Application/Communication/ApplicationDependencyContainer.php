<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication;

use SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider\ExceptionService\DefaultExceptionHandler;
use SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider\ExceptionService\ExceptionHandlerInterface;
use SprykerEngine\Yves\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Symfony\Component\HttpFoundation\Response;

class ApplicationDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ExceptionHandlerInterface[]
     */
    public function createExceptionHandlers()
    {
        return [
            Response::HTTP_NOT_FOUND => new DefaultExceptionHandler(),
        ];
    }

}
