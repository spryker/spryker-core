<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Exception;

use Exception;

class AclEntityRuleReferencedEntityNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected const MESSAGE_TEMPLATE = 'Referenced entity class %s was not found.';

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
