<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Runner;

use Generated\Shared\Transfer\DynamicFixturesRequestBackendApiAttributesTransfer;
use Spryker\Glue\TestifyBackendApi\Processor\Synchronizer\OperationSynchronizerInterface;

class OperationPostRunner implements OperationPostRunnerInterface
{
    /**
     * @var \Spryker\Glue\TestifyBackendApi\Processor\Synchronizer\OperationSynchronizerInterface
     */
    protected OperationSynchronizerInterface $operationSynchronizer;

    /**
     * @param \Spryker\Glue\TestifyBackendApi\Processor\Synchronizer\OperationSynchronizerInterface $operationSynchronizer
     */
    public function __construct(OperationSynchronizerInterface $operationSynchronizer)
    {
        $this->operationSynchronizer = $operationSynchronizer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicFixturesRequestBackendApiAttributesTransfer $dynamicFixturesRequestBackendApiAttributesTransfer
     *
     * @return void
     */
    public function executePostOperations(
        DynamicFixturesRequestBackendApiAttributesTransfer $dynamicFixturesRequestBackendApiAttributesTransfer
    ): void {
        if ($dynamicFixturesRequestBackendApiAttributesTransfer->getSynchronize()) {
            $this->operationSynchronizer->synchronize();
        }
    }
}
