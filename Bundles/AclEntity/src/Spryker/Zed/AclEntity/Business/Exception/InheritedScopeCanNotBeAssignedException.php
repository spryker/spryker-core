<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Exception;

use Exception;

class InheritedScopeCanNotBeAssignedException extends Exception
{
    /**
     * @var string
     */
    protected const MESSAGE_TEMPLATE = 'Scope inherited rule can not be assigned to %s entity.';

    /**
     * @param string $entity
     */
    public function __construct(string $entity)
    {
        $message = sprintf(
            static::MESSAGE_TEMPLATE,
            $entity
        );
        parent::__construct($message);
    }
}
