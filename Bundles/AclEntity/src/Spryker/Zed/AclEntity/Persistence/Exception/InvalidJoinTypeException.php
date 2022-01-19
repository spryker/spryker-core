<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Exception;

use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class InvalidJoinTypeException extends AclEntityException
{
    /**
     * @var string
     */
    protected const MESSAGE_TEMPLATE = 'Unsupported join type given: %s. Use one of: %s';

    /**
     * @param string $joinType
     */
    public function __construct(string $joinType)
    {
        $supportedJoinTypes = [
            Criteria::LEFT_JOIN,
            Criteria::INNER_JOIN,
            Criteria::RIGHT_JOIN,
        ];

        parent::__construct(sprintf(static::MESSAGE_TEMPLATE, $joinType, implode(', ', $supportedJoinTypes)));
    }
}
