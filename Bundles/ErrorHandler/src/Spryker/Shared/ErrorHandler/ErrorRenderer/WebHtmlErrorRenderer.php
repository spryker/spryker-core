<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler\ErrorRenderer;

use Spryker\Shared\Config\Config;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;

class WebHtmlErrorRenderer implements ErrorRendererInterface
{

    const APPLICATION_ZED = 'ZED';

    /**
     * @var string
     */
    protected $application;

    /**
     * @param string $application
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * @param \Exception|\Throwable $exception
     *
     * @return string
     */
    public function render($exception)
    {
        $errorPage = $this->getErrorPageForApplication();

        return $this->getHtmlErrorPageContent($errorPage);
    }

    /**
     * @return string
     */
    protected function getErrorPageForApplication()
    {
        if ($this->application === static::APPLICATION_ZED) {
            return Config::get(ErrorHandlerConstants::ZED_ERROR_PAGE);
        }

        return Config::get(ErrorHandlerConstants::YVES_ERROR_PAGE);
    }

    /**
     * @param string $errorPage
     *
     * @return string
     */
    protected function getHtmlErrorPageContent($errorPage)
    {
        return file_get_contents($errorPage);
    }

}
