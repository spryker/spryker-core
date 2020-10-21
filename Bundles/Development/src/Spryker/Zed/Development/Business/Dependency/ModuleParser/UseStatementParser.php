<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\ModuleParser;

use Symfony\Component\Finder\SplFileInfo;

class UseStatementParser implements UseStatementParserInterface
{
    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return array
     */
    public function getUseStatements(SplFileInfo $fileInfo): array
    {
        return $this->getUseStatementsInFile($fileInfo);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return array
     */
    protected function getUseStatementsInFile(SplFileInfo $fileInfo): array
    {
        preg_match_all('/^(use\s([^\s|;]+))/m', $fileInfo->getContents(), $matches);

        $useStatements = [];
        foreach ($matches[2] as $useStatement) {
            $useStatements[] = $useStatement;
        }

        return $useStatements;
    }
}
