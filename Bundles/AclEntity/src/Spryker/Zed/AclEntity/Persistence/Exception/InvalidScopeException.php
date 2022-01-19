<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Exception;

use Spryker\Shared\AclEntity\AclEntityConstants;

class InvalidScopeException extends AclEntityException
{
    /**
     * @var string
     */
    protected const MESSAGE_TEMPLATE = 'Unsupported AclEntity scope given: %s. Use one of: %s';

    /**
     * @param string $scope
     */
    public function __construct(string $scope)
    {
        $supportedScopes = [
            AclEntityConstants::SCOPE_GLOBAL,
            AclEntityConstants::SCOPE_INHERITED,
            AclEntityConstants::SCOPE_SEGMENT,
        ];

        parent::__construct(sprintf(static::MESSAGE_TEMPLATE, $scope, implode(', ', $supportedScopes)));
    }
}
