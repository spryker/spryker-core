<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController
{

    /**
     * @param FlattenException $exception
     * @param string $format
     *
     * @return Response
     */
    public function showAction(FlattenException $exception, $format = 'html')
    {
        return new Response($exception->getMessage());
    }

}
