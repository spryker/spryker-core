<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Request;

use Spryker\Zed\FactFinder\Business\Api\FFConnector;

abstract class AbstractRequest implements RequestInterface
{

    /**
     * @var FFConnector
     */
    protected $ffConnector;

    /**
     * @param FFConnector $ffConnector
     */
    public function __construct(FFConnector $ffConnector)
    {
        $this->ffConnector = $ffConnector;
    }
}
