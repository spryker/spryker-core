<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Exception;

class OperationNotAuthorizedException extends AclEntityException
{
    /**
     * @var string
     */
    public const MESSAGE_TEMPLATE = 'Operation "%s" is restricted for %s';
}
