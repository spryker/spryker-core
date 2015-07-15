<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Workflow;

/**
 * Interface DefinitionInterface
 */
interface DefinitionInterface
{

    /**
     * @param array $logContext
     *
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function run(array $logContext);

}
