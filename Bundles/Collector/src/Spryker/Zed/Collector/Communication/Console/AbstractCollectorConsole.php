<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
        $summary = 'Export to Yves finished:' . PHP_EOL . PHP_EOL;

        foreach ($results as $type => $result) {
            $summary .= sprintf(
                '<fg=yellow>Export for %s</fg=yellow> %s:' . PHP_EOL .
                '<fg=white>Total:</fg=white> %d' . PHP_EOL .
                '<fg=white>Processed:</fg=white> %d' . PHP_EOL .
                '<fg=white>Succeeded:</fg=white> %d' . PHP_EOL .
                '<fg=white>Deleted:</fg=white> %d' . PHP_EOL .
                '<fg=white>Failed:</fg=white> %d' . PHP_EOL . PHP_EOL,
                $type,
                $result->isFailed() ? '<fg=red>failed</fg=red>' : 'finished successful',
                $result->getTotalCount(),
                $result->getProcessedCount(),
                $result->getSuccessCount(),
                $result->getDeletedCount(),
                $result->getFailedCount()
            );
        }

        return $summary;
    }

}
