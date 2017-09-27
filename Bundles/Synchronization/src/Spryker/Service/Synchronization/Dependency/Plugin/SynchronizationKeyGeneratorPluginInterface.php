<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization\Dependency\Plugin;

use Generated\Shared\Transfer\SynchronizationDataTransfer;

interface SynchronizationKeyGeneratorPluginInterface
{

    /**
     * Specification:
     * - Generates storage or search key based on SynchronizationDataTransfer
     * for entities which use synchronization
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $dataTransfer
     *
     * @return string
     */
    public function generateKey(SynchronizationDataTransfer $dataTransfer);

}
