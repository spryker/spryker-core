<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index;

use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Psr\Log\LoggerInterface;

interface InstallerInterface
{
    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinitionTransfer
     *
     * @return bool
     */
    public function accept(IndexDefinitionTransfer $indexDefinitionTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinitionTransfer
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function run(IndexDefinitionTransfer $indexDefinitionTransfer, LoggerInterface $logger): void;
}
