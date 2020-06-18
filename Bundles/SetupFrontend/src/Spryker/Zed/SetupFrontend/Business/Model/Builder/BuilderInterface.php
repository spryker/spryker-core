<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Builder;

use Generated\Shared\Transfer\SetupFrontendConfigurationTransfer;
use Psr\Log\LoggerInterface;

interface BuilderInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Generated\Shared\Transfer\SetupFrontendConfigurationTransfer $setupFrontendConfigurationTransfer
     *
     * @return bool
     */
    public function build(LoggerInterface $logger, SetupFrontendConfigurationTransfer $setupFrontendConfigurationTransfer);
}
