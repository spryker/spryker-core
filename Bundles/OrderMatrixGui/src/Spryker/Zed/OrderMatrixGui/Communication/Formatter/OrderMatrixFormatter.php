<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrixGui\Communication\Formatter;

use Spryker\Zed\OrderMatrixGui\Communication\DataExtractor\OrderMatrixDataExtractorInterface;
use Spryker\Zed\OrderMatrixGui\Dependency\Service\OrderMatrixGuiToUtilSanitizeServiceInterface;
use Spryker\Zed\OrderMatrixGui\OrderMatrixGuiConfig;

class OrderMatrixFormatter implements OrderMatrixFormatterInterface
{
    /**
     * @var string
     */
    protected const COL_STATE = 'COL_STATE';

    /**
     * @var string
     */
    protected const SALES_URL_HTML_PLACEHOLDER = '<a href="%s">%s</a>';

    /**
     * @var string
     */
    protected const PROCESS_KEY_PLACEHOLDER = '%s:%s';

    /**
     * @var string
     */
    protected const KEY_DAY = 'day';

    /**
     * @var string
     */
    protected const KEY_WEEK = 'week';

    /**
     * @var string
     */
    protected const KEY_OTHER = 'other';

    /**
     * @var string
     */
    protected const PIPE_DELIMITER = ' | ';

    /**
     * @var string
     */
    protected const COLON_DELIMITER = ':';

    /**
     * @param \Spryker\Zed\OrderMatrixGui\Dependency\Service\OrderMatrixGuiToUtilSanitizeServiceInterface $utilSanitizeService
     * @param \Spryker\Zed\OrderMatrixGui\Communication\DataExtractor\OrderMatrixDataExtractorInterface $orderMatrixDataExtractor
     * @param \Spryker\Zed\OrderMatrixGui\OrderMatrixGuiConfig $orderMatrixGuiConfig
     */
    public function __construct(
        protected OrderMatrixGuiToUtilSanitizeServiceInterface $utilSanitizeService,
        protected OrderMatrixDataExtractorInterface $orderMatrixDataExtractor,
        protected OrderMatrixGuiConfig $orderMatrixGuiConfig
    ) {
    }

    /**
     * @param array<string, array<string, array<string>>> $orderMatrix
     *
     * @return array<int, array<string>>
     */
    public function formatOrderMatrix(array $orderMatrix): array
    {
        $processNames = $this->orderMatrixDataExtractor->extractProcessNames($orderMatrix);
        $stateNames = $this->orderMatrixDataExtractor->extractStateNames($orderMatrix);
        $results = [$this->getHeaderColumns($processNames)];

        foreach ($orderMatrix as $stateKey => $grid) {
            $results[] = $this->formatStateResult($stateKey, $grid, $processNames, $stateNames);
        }

        return $results;
    }

    /**
     * @param string $stateKey
     * @param array<string, array<string>> $grid
     * @param array<string> $processNames
     * @param array<string> $stateNames
     *
     * @return array<string>
     */
    protected function formatStateResult(string $stateKey, array $grid, array $processNames, array $stateNames): array
    {
        [$stateId, $stateName] = explode(static::COLON_DELIMITER, $stateKey, 2);
        $result = [
            static::COL_STATE => $stateNames[(int)$stateId],
        ];

        foreach ($processNames as $processId => $processName) {
            $processKey = sprintf(static::PROCESS_KEY_PLACEHOLDER, $processId, $processName);
            $element = '';
            if (!empty($grid[$processKey])) {
                $element = $this->formatElement($grid[$processKey], (int)$processId, (int)$stateId);
            }

            $result[$processName] = $element;
        }

        return $result;
    }

    /**
     * @param array<string> $processNames
     *
     * @return array<string>
     */
    protected function getHeaderColumns(array $processNames): array
    {
        $headersColumns = [
            static::COL_STATE => '',
        ];
        foreach ($processNames as $processName) {
            $headersColumns[$processName] = $processName;
        }

        return $headersColumns;
    }

    /**
     * @param array<string> $gridInput
     * @param int $idProcess
     * @param int $idState
     *
     * @return string
     */
    protected function formatElement(array $gridInput, int $idProcess, int $idState): string
    {
        $grid = array_replace([
            static::KEY_DAY => 0,
            static::KEY_WEEK => 0,
            static::KEY_OTHER => 0,
        ], $gridInput);

        foreach ($grid as $key => $value) {
            if (!$value) {
                $grid[$key] = $value;

                continue;
            }

            $url = sprintf($this->orderMatrixGuiConfig->getSalesUrlPlaceholder(), $idProcess, $idState, $key);
            $grid[$key] = sprintf(static::SALES_URL_HTML_PLACEHOLDER, $this->utilSanitizeService->escapeHtml($url), $value);
        }

        return implode(static::PIPE_DELIMITER, $grid);
    }
}
