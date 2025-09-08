<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser;

abstract class AbstractAssignmentFileParser
{
    /**
     * @var string
     */
    protected const KEY_ASSIGN = 'assign';

    /**
     * @var string
     */
    protected const KEY_DEASSIGN = 'deassign';

    /**
     * @var string
     */
    protected const SECTION_ATTACH = 'attach';

    /**
     * @var string
     */
    protected const SECTION_DETACH = 'detach';

    /**
     * @param string $content
     * @param string $attachHeader
     * @param string $detachHeader
     *
     * @return array<string, array<int>>
     */
    protected function parseAssignmentFile(string $content, string $attachHeader, string $detachHeader): array
    {
        $lines = explode(PHP_EOL, $content);
        $idsToAssign = [];
        $idsToDeassign = [];
        $currentSection = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if (!$line) {
                continue;
            }

            $newSection = $this->detectSection($line, $attachHeader, $detachHeader);
            if ($newSection !== null) {
                $currentSection = $newSection;

                continue;
            }

            $this->processLineForSection($line, $currentSection, $idsToAssign, $idsToDeassign);
        }

        return [
            static::KEY_ASSIGN => array_unique($idsToAssign),
            static::KEY_DEASSIGN => array_unique($idsToDeassign),
        ];
    }

    protected function detectSection(string $line, string $attachHeader, string $detachHeader): ?string
    {
        if (stripos($line, $attachHeader) !== false) {
            return static::SECTION_ATTACH;
        }

        if (stripos($line, $detachHeader) !== false) {
            return static::SECTION_DETACH;
        }

        return null;
    }

    /**
     * @param string $line
     * @param string|null $currentSection
     * @param array<int> $idsToAssign
     * @param array<int> $idsToDeassign
     *
     * @return void
     */
    protected function processLineForSection(string $line, ?string $currentSection, array &$idsToAssign, array &$idsToDeassign): void
    {
        $id = $this->extractId($line);
        if ($id === null) {
            return;
        }

        $sectionTargets = [
            static::SECTION_ATTACH => &$idsToAssign,
            static::SECTION_DETACH => &$idsToDeassign,
        ];

        if (isset($sectionTargets[$currentSection])) {
            $sectionTargets[$currentSection][] = $id;
        }
    }

    protected function extractId(string $line): ?int
    {
        $trimmedLine = trim($line);

        if (is_numeric($trimmedLine)) {
            return (int)$trimmedLine;
        }

        return null;
    }

    /**
     * @param array<string> $lines
     * @param string $sectionName
     * @param string $sectionPrefix
     *
     * @return array<string>
     */
    protected function extractSection(array $lines, string $sectionName, string $sectionPrefix): array
    {
        $references = [];
        $inSection = false;

        foreach ($lines as $line) {
            if ($line === $sectionName) {
                $inSection = true;

                continue;
            }

            if (str_starts_with($line, $sectionPrefix)) {
                $inSection = false;
            }

            if ($inSection && $line) {
                $references[] = $line;
            }
        }

        return $references;
    }
}
