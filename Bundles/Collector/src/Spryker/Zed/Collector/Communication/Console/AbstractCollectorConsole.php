<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;

abstract class AbstractCollectorConsole extends Console
{

    /**
     * @param array|\Spryker\Zed\Collector\Business\Model\BatchResultInterface[] $resultData
     *
     * @return string
     *
     * @todo move into template
     */
    protected function buildSummary(array $resultData)
    {
        if (empty($resultData)) {
            return PHP_EOL . '<fg=yellow>Nothing exported.</fg=yellow>' . PHP_EOL;
        }

        $summary = PHP_EOL;

        ksort($resultData);
        foreach ($resultData as $type => $result) {
            $summary .= sprintf(
                '<fg=green>%s</fg=green><fg=yellow> </fg=yellow><fg=yellow></fg=yellow>' . PHP_EOL .
                '<fg=white>Total: %d</fg=white>' . PHP_EOL .
                '<fg=white>Processed: %d</fg=white>' . PHP_EOL .
                '<fg=white>Succeeded: %s</fg=white>' . PHP_EOL .
                '<fg=white>Deleted: %s</fg=white>' . PHP_EOL .
                '<fg=white>Failed: %s </fg=white>' . PHP_EOL,
                mb_strtoupper($type),
                $result->getTotalCount(),
                $result->getProcessedCount(),
                $result->getSuccessCount() > 0 ? '<fg=green>' . $result->getSuccessCount() . '</fg=green>' : $result->getSuccessCount(),
                $result->getDeletedCount() > 0 ? '<fg=yellow>' . $result->getDeletedCount() . '</fg=yellow>' : $result->getDeletedCount(),
                $result->isFailed() ? '<fg=red>' . $result->getFailedCount() . '</fg=red>' : $result->getFailedCount()
            );

            $summary .= PHP_EOL;
        }

        return $summary . PHP_EOL;
    }

    /**
     * @param array|\Spryker\Zed\Collector\Business\Model\BatchResultInterface[] $results
     *
     * @return string
     */
    protected function buildNestedSummary(array $results)
    {
        $summary = '';
        foreach ($results as $localeName => $summaryData) {
            $summary .= PHP_EOL;
            $summary .= sprintf('<fg=yellow>Locale:</fg=yellow> <fg=white>%s</fg=white>', $localeName);
            $summary .= PHP_EOL;
            $summary .= '<fg=yellow>-------------</fg=yellow>';
            $summary .= $this->buildSummary($summaryData);
        }

        $summary .= PHP_EOL . 'All done.' . PHP_EOL;

        return $summary;
    }

}
