<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionFile\Communication\Handler;

use Spryker\Shared\SessionFile\Handler\AbstractSessionAccountHandlerFile;
use Spryker\Shared\SessionFile\Hasher\HasherInterface;
use Spryker\Zed\SessionFile\SessionFileConfig;

class SessionUserHandlerFile extends AbstractSessionAccountHandlerFile
{
    /**
     * @var \Spryker\Zed\SessionFile\SessionFileConfig
     */
    protected $sessionFileConfig;

    /**
     * @param \Spryker\Shared\SessionFile\Hasher\HasherInterface $hasher
     * @param \Spryker\Zed\SessionFile\SessionFileConfig $sessionFileConfig
     */
    public function __construct(HasherInterface $hasher, SessionFileConfig $sessionFileConfig)
    {
        parent::__construct($hasher);
        $this->sessionFileConfig = $sessionFileConfig;
    }

    /**
     * @return string
     */
    protected function getAccountType(): string
    {
        return 'user';
    }

    /**
     * @return string
     */
    protected function getActiveSessionFilePath(): string
    {
        return $this->sessionFileConfig->getActiveSessionFilePath();
    }
}
