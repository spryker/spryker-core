<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Development\Business\DevelopmentFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method DevelopmentFacade getFacade()
 */
class DependencyController extends AbstractController
{

    /**
     * @return StreamedResponse
     */
    public function graphAction()
    {
        $callback = function() {
            $this->getFacade()->drawDependencyTreeGraph();
        };

        return $this->streamedResponse($callback);
    }

}
