<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;

abstract class AbstractCollectorConsole extends Console
{

    /**
     * @param array|\Spryker\Zed\Collector\Business\Model\BatchResult[] $results
     *
     * @return string
     *
     * @todo move into template
     */
    protected function buildSummary(array $results)
    {
        $summary = PHP_EOL . PHP_EOL;

        foreach ($results as $type => $result) {
            $summary .= sprintf(
                '<fg=yellow>Export Details for </fg=yellow><fg=green>%s</fg=green>' . PHP_EOL .
                '<fg=white>Total: %d</fg=white>' . PHP_EOL .
                '<fg=white>Processed: %d</fg=white>' . PHP_EOL .
                '<fg=white>Succeeded: %s</fg=white>' . PHP_EOL .
                '<fg=white>Deleted: %s</fg=white>' . PHP_EOL .
                '<fg=white>Failed: %s </fg=white>' . PHP_EOL . PHP_EOL,
                $type,
                $result->getTotalCount(),
                $result->getProcessedCount(),
                $result->getSuccessCount() > 0 ? '<fg=green>' . $result->getSuccessCount() . '</fg=green>' : $result->getSuccessCount(),
                $result->getDeletedCount() > 0 ? '<fg=yellow>' . $result->getDeletedCount() . '</fg=yellow>' : $result->getDeletedCount(),
                $result->isFailed() ? '<fg=red>' . $result->getFailedCount() . '</fg=red>' : $result->getFailedCount()
            );
        }

        $summary .= 'Export to Yves finished.' . PHP_EOL;

        return $summary;
    }

}
