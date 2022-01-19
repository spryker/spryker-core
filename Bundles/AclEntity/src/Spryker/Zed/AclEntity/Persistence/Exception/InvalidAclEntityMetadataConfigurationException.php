<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Exception;

class InvalidAclEntityMetadataConfigurationException extends AclEntityException
{
    /**
     * @var string
     */
    protected const MESSAGE = 'Invalid AclEntityMetadata configuration given.';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }
}
