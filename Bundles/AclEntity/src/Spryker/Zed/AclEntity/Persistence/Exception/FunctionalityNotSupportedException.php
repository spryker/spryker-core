<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Exception;

class FunctionalityNotSupportedException extends AclEntityException
{
    /**
     * @var string
     */
    public const SUB_ENTITY_NOT_SUPPORTED_MESSAGE = 'Sub entity functionality is not supported for bulk delete query';

    /**
     * @var string
     */
    public const INHERITED_SCOPE_NOT_SUPPORTED_MESSAGE = 'Bulk delete query is not supported in Inherited scope';
}
