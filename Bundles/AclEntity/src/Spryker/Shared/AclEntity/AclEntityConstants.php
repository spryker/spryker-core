<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AclEntity;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AclEntityConstants
{
    public const OPERATION_MASK_READ = 0b1;
    public const OPERATION_MASK_CREATE = 0b10;
    public const OPERATION_MASK_UPDATE = 0b100;
    public const OPERATION_MASK_DELETE = 0b1000;
    public const OPERATION_MASK_CRUD = 0b1111;

    public const OPERATION_CREATE = 'create';
    public const OPERATION_UPDATE = 'update';
    public const OPERATION_DELETE = 'delete';

    public const SCOPE_GLOBAL = 'global';
    public const SCOPE_SEGMENT = 'segment';
    public const SCOPE_INHERITED = 'inherited';
    public const SCOPE_DEFAULT = 'default';

    public const WHILDCARD_ENTITY = '*';
}
