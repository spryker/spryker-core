<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Dependency\Service;

class CompanyUserInvitationToUtilTextBridge implements CompanyUserInvitationToUtilTextInterface
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
     * @param string $value
     * @param string $algorithm
     *
     * @return string
     */
    public function hashValue(string $value, string $algorithm): string
    {
        return $this->utilTextService->hashValue($value, $algorithm);
    }
}
