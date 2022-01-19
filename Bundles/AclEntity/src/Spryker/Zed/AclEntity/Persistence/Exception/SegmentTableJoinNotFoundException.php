<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Exception;

class SegmentTableJoinNotFoundException extends AclEntityException
{
    /**
     * @var string
     */
    protected const MESSAGE = 'Segment table join was not found.';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }
}
