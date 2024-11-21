<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Customer\Session;

use Spryker\Service\UtilText\UtilTextServiceInterface;

class AnonymousIdProvider implements AnonymousIdProviderInterface
{
    protected UtilTextServiceInterface $utilTextService;

    /**
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     */
    public function __construct(UtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @return string
     */
    public function generateUniqueId(): string
    {
        return 'anonymous-' . $this->utilTextService->generateRandomString(16);
    }
}
