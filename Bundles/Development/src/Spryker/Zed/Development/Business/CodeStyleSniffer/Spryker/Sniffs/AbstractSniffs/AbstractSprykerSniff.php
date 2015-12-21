<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\AbstractSniffs;

abstract class AbstractSprykerSniff implements \PHP_CodeSniffer_Sniff
{

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return string
     */
    protected function getBundle(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);
        $classNameParts = explode('\\', $className);

        return $classNameParts[2];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return string
     */
    protected function getLayer(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);
        $classNameParts = explode('\\', $className);

        return $classNameParts[3];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return string
     */
    protected function getClassName(\PHP_CodeSniffer_File $phpCsFile)
    {
        $fileName = $phpCsFile->getFilename();
        $fileNameParts = explode(DIRECTORY_SEPARATOR, $fileName);
        $sourceDirectoryPosition = array_search('src', array_values($fileNameParts));
        $classNameParts = array_slice($fileNameParts, $sourceDirectoryPosition + 1);
        $className = implode('\\', $classNameParts);
        $className = str_replace('.php', '', $className);

        return $className;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     * @param array $missingUses
     *
     * @return void
     */
    protected function addUseStatements(\PHP_CodeSniffer_File $phpCsFile, $stackPointer, array $missingUses)
    {
        $useStatements = $this->parseUseStatements($phpCsFile, $stackPointer);
        foreach ($missingUses as $missingUse) {
            if (!in_array($missingUse, $useStatements)) {
                $this->addMissingUse($phpCsFile, $stackPointer, $missingUse);
            }
        }
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param $stackPointer
     *
     * @return array
     */
    protected function parseUseStatements(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $useStatements = [];
        $tokens = $phpCsFile->getTokens();
        if ($phpCsFile->findPrevious(T_USE, $stackPointer)) {
            $position = $phpCsFile->findPrevious(T_USE, $stackPointer);
            while ($position !== false) {
                $position = $phpCsFile->findPrevious(T_USE, $position);
                if ($position !== false) {
                    $end = $phpCsFile->findEndOfStatement($position);
                    if ($tokens[$position]['type'] === 'T_USE') {
                        $useTokens = array_slice($tokens, $position + 2, $end - $position - 2);
                        $useStatements[] = $this->parseUseParts($useTokens);
                    }
                }
                $position--;
            }
        }

        return $useStatements;
    }

    /**
     * @param array $useTokens
     *
     * @return string
     */
    protected function parseUseParts(array $useTokens)
    {
        $useClass = '';
        foreach ($useTokens as $useToken) {
            $useClass .= $useToken['content'];
        }

        return $useClass;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     * @param string $missingUse
     *
     * @return void
     */
    protected function addMissingUse(\PHP_CodeSniffer_File $phpCsFile, $stackPointer, $missingUse)
    {
        $previousUsePosition = $phpCsFile->findPrevious(T_USE, $stackPointer);
        if ($previousUsePosition !== false) {
            $endOfLastUse = $phpCsFile->findEndOfStatement($previousUsePosition);

            $phpCsFile->fixer->addNewline($endOfLastUse);
            $phpCsFile->fixer->addContent($endOfLastUse, 'use ' . $missingUse . ';');
        }
    }

}
