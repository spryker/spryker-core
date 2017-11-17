<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Dependency\Facade;

class PropelOrmToLogBridge implements PropelOrmToLogInterface
{
    /**
     * @var \Spryker\Zed\Log\Business\LogFacadeInterface
     */
    protected $logFacade;

    /**
     * @param \Spryker\Zed\Log\Business\LogFacadeInterface $logFacade
     */
    public function __construct($logFacade)
    {
        $this->logFacade = $logFacade;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data)
    {
        return $this->logFacade->sanitize($data);
    }
}
