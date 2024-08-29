<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrixGui\Communication\DataExtractor;

class OrderMatrixDataExtractor implements OrderMatrixDataExtractorInterface
{
    /**
     * @var string
     */
    protected const COLON_DELIMITER = ':';

    /**
     * @param array<string, array<string, array<string>>> $orderMatrix
     *
     * @return array<int, string>
     */
    public function extractProcessNames(array $orderMatrix): array
    {
        $processNames = [];
        foreach ($orderMatrix as $processes) {
            foreach ($processes as $processKey => $dateWindow) {
                [$processId, $processName] = explode(static::COLON_DELIMITER, $processKey, 2);
                $processNames[(int)$processId] = $processNames[(int)$processId] ?? $processName;
            }
        }

        return $processNames;
    }

    /**
     * @param array<string, array<string, array<string>>> $orderMatrix
     *
     * @return array<int, string>
     */
    public function extractStateNames(array $orderMatrix): array
    {
        $stateNames = [];
        foreach ($orderMatrix as $stateKey => $processes) {
            [$stateId, $stateName] = explode(static::COLON_DELIMITER, $stateKey, 2);
            if (isset($stateNames[(int)$stateId])) {
                continue;
            }

            $stateNames[(int)$stateId] = $stateName;
        }

        return $stateNames;
    }
}
