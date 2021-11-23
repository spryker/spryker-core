<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Exception;

class RelationNotFoundException extends AclEntityException
{
    /**
     * @var string
     */
    protected const MESSAGE_TEMPLATE = 'Failed to find relation "%s" for "%s"';

    /**
     * @param string $relation
     * @param string $class
     */
    public function __construct(string $relation, string $class)
    {
        parent::__construct(sprintf(static::MESSAGE_TEMPLATE, $relation, $class));
    }
}
