<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManagerAws\Dependency\Service;

class SecretsManagerAwsToUtilTextServiceBridge implements SecretsManagerAwsToUtilTextServiceInterface
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
     * @param mixed $value
     * @param string $algorithm
     *
     * @return string
     */
    public function hashValue($value, string $algorithm): string
    {
        return $this->utilTextService->hashValue($value, $algorithm);
    }
}
