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
        return $this->utilTextService->camelCaseToSeparator($string);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function dashToCamelCase($string)
    {
        return $this->utilTextService->separatorToCamelCase($string);
    }
}
