<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\ModuleParser;

use Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface;
use Symfony\Component\Finder\SplFileInfo;

class UseStatementParser implements UseStatementParserInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface
     */
    protected $finder;

    /**
     * @var array
     */
    protected static $moduleUseStatement = [];

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface $finder
     */
    public function __construct(FinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param string $module
     *
     * @return array
     */
    public function getUseStatements(string $module): array
    {
        if (!isset(static::$moduleUseStatement[$module])) {
            static::$moduleUseStatement[$module] = $this->findModuleUseStatements($module);
        }

        return static::$moduleUseStatement[$module];
    }

    /**
     * @param string $module
     *
     * @return array
     */
    protected function findModuleUseStatements(string $module): array
    {
        $useStatements = [];
        $files = $this->finder->find($module);

        foreach ($files as $file) {
            $fileUseStatements = $this->getUseStatementsInFile($file);
            if (count($fileUseStatements) > 0) {
                $useStatements[$file->getRealPath()] = $this->getUseStatementsInFile($file);
            }
        }

        return $useStatements;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $file
     *
     * @return array
     */
    protected function getUseStatementsInFile(SplFileInfo $file): array
    {
        preg_match_all('#use (.*);#', $file->getContents(), $matches);

        $useStatements = [];
        foreach ($matches[1] as $useStatement) {
            $useStatements[] = $useStatement;
        }

        return $useStatements;
    }
}
