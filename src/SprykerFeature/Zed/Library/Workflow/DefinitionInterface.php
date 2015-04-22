<?php
namespace SprykerFeature\Zed\Library\Workflow;

/**
 * Interface DefinitionInterface
 * @package SprykerFeature\Zed\Library\Workflow
 */
interface DefinitionInterface
{
    /**
     * @param array $logContext
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function run(array $logContext);
}
