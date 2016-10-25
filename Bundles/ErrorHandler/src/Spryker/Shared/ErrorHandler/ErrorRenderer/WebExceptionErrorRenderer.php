<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler\ErrorRenderer;

use Exception;

class WebExceptionErrorRenderer implements ErrorRendererInterface
{

    /**
     * @param \Exception $exception
     *
     * @return string
     */
    public function render(Exception $exception)
    {
        $errorMessageTemplate =
            '<div style="font-family: courier; font-size: 14px">'
            . '<h1>%s Exception</h1>'
            . '<div style="background: #dadada; padding: 5px"><font style="12"><b>%s - %s</b></font></div><br />'
            . '<p>in %s (%s)</p>'
            . '<p><b>Url:</b>%s</p>'
            . '<p><b>Trace:</b></p>'
            . '<pre>%s</pre>'
            . '</div>';

        $errorMessage = sprintf(
            $errorMessageTemplate,
            APPLICATION,
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $this->getUri(),
            $exception->getTraceAsString()
        );

        return $errorMessage;
    }

    /**
     * @return string
     */
    protected function getUri()
    {
        $uri = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : 'n/a';

        return htmlspecialchars($uri, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

}
