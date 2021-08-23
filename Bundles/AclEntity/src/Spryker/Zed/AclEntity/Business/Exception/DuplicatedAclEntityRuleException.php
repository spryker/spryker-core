<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Exception;

use Exception;

class DuplicatedAclEntityRuleException extends Exception
{
    protected const MESSAGE_TEMPLATE = 'Acl entity rule is duplicated for %s entity, %s role id.';

    /**
     * @param string $entity
     * @param int $idRole
     */
    public function __construct(string $entity, int $idRole)
    {
        $message = sprintf(
            static::MESSAGE_TEMPLATE,
            $entity,
            $idRole
        );
        parent::__construct($message);
    }
}
