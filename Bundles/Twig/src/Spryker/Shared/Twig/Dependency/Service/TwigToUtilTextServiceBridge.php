<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Dependency\Service;

class TwigToUtilTextServiceBridge implements TwigToUtilTextServiceInterface
{
    /**
     * @var \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     */
    public function __construct($utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function camelCaseToDash($string)
    {
        return $this->utilTextService->camelCaseToDash($string);
    }

    /**
     * @param string $string
     * @param bool $upperCaseFirst
     *
     * @return string
     */
    public function dashToCamelCase($string, $upperCaseFirst = false)
    {
        return $this->utilTextService->dashToCamelCase($string, $upperCaseFirst);
    }
}
