<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Dependency\Service;

class CustomersRestApiToUtilTextServiceBridge implements CustomersRestApiToUtilTextServiceInterface
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
     * @param string $separator
     *
     * @return string
     */
    public function camelCaseToSeparator(string $string, string $separator = '-'): string
    {
        return $this->utilTextService->camelCaseToSeparator($string, $separator);
    }
}
