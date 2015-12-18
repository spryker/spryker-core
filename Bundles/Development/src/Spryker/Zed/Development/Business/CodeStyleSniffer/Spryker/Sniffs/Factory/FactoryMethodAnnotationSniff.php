<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Factory;

class FactoryMethodAnnotationSniff implements \PHP_CodeSniffer_Sniff
{

    /**
     * @var array
     */
    protected $useStatements = [];

    /**
     * @return array
     */
    public function register()
    {
        return [
            T_CLASS,
        ];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $tokens = $phpCsFile->getTokens();
        if ($this->isFactory($phpCsFile)) {
            $bundle = $this->getBundle($phpCsFile);
            $queryContainerName = $bundle . 'QueryContainer';
            $configName = $bundle . 'Config';

            if ($this->hasDocBlock($stackPointer, $tokens)) {
                if (!$this->hasConfigAnnotation($phpCsFile, $stackPointer)) {
                    $fix = $phpCsFile->addFixableError('getConfig() annotation missing', $stackPointer);
                    if ($fix) {
                        $phpCsFile->fixer->beginChangeset();
                        $this->addUseStatements(
                            $phpCsFile,
                            $stackPointer,
                            [$this->getConfigClassName($phpCsFile)]
                        );
                        $this->addMissingAnnotation(
                            $phpCsFile,
                            $stackPointer,
                            $configName . ' getConfig()'
                        );
                        $phpCsFile->fixer->endChangeset();
                    }
                }

                if (!$this->hasQueryContainerAnnotation($phpCsFile, $stackPointer)) {
                    $fix = $phpCsFile->addFixableError('getQueryContainer() annotation missing', $stackPointer);
                    if ($fix) {
                        $phpCsFile->fixer->beginChangeset();
                        $this->addUseStatements(
                            $phpCsFile,
                            $stackPointer,
                            [$this->getQueryContainerClassName($phpCsFile)]
                        );
                        $this->addMissingAnnotation(
                            $phpCsFile,
                            $stackPointer,
                            $queryContainerName . ' getQueryContainer()'
                        );
                        $phpCsFile->fixer->endChangeset();
                    }
                }

                return;
            }

            $fix = $phpCsFile->addFixableError('getQueryContainer() and getConfig() annotation missing', $stackPointer);
            if ($fix) {
                $phpCsFile->fixer->beginChangeset();

                $this->addUseStatements(
                    $phpCsFile,
                    $stackPointer,
                    [
                        $this->getConfigClassName($phpCsFile),
                        $this->getQueryContainerClassName($phpCsFile),
                    ]
                );

                $phpCsFile->fixer->addNewlineBefore($stackPointer);
                $phpCsFile->fixer->addContentBefore($stackPointer, ' */');
                $phpCsFile->fixer->addNewlineBefore($stackPointer);
                $phpCsFile->fixer->addContentBefore($stackPointer, ' * @method ' . $queryContainerName . ' getQueryContainer()');
                $phpCsFile->fixer->addNewlineBefore($stackPointer);
                $phpCsFile->fixer->addContentBefore($stackPointer, ' * @method ' . $configName . ' getConfig()');
                $phpCsFile->fixer->addNewlineBefore($stackPointer);
                $phpCsFile->fixer->addContentBefore($stackPointer, '/**');
                $phpCsFile->fixer->addNewlineBefore($stackPointer);
                $phpCsFile->fixer->addNewlineBefore($stackPointer);

                $phpCsFile->fixer->endChangeset();
            }
        }
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return bool
     */
    protected function isFactory(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);

        return (
            substr($className, -15) === 'BusinessFactory'
            || substr($className, -20) === 'CommunicationFactory'
            || substr($className, -18) === 'PersistenceFactory'
        );
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
     * @param int $stackPointer
     * @param array $tokens
     *
     * @return bool
     */
    private function hasDocBlock($stackPointer, array $tokens)
    {
        return ($tokens[$stackPointer - 2]['type'] === 'T_DOC_COMMENT_CLOSE_TAG');
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return array
     */
    private function getQueryContainerClassName(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);
        $classNameParts = explode('\\', $className);
        $classNameParts = array_slice($classNameParts, 0, -2);
        $bundleName = $classNameParts[2];
        array_push($classNameParts, $bundleName . 'QueryContainer');
        $queryContainerClassName = implode('\\', $classNameParts);

        return $queryContainerClassName;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return array
     */
    private function getConfigClassName(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);
        $classNameParts = explode('\\', $className);
        $classNameParts = array_slice($classNameParts, 0, -2);
        $bundleName = $classNameParts[2];
        array_push($classNameParts, $bundleName . 'Config');
        $configClassName = implode('\\', $classNameParts);

        return $configClassName;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return string
     */
    private function getBundle(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);
        $classNameParts = explode('\\', $className);
        $classNameParts = array_slice($classNameParts, 0, -2);

        return $classNameParts[2];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     * @param array $missingUses
     *
     * @return void
     */
    private function addUseStatements(\PHP_CodeSniffer_File $phpCsFile, $stackPointer, array $missingUses)
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
     * @param int $stackPointer
     * @param string $missingAnnotation
     *
     * @return void
     */
    private function addMissingAnnotation(\PHP_CodeSniffer_File $phpCsFile, $stackPointer, $missingAnnotation)
    {
        $position = $phpCsFile->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $stackPointer);
        if ($position !== false) {
            $phpCsFile->fixer->addNewlineBefore($position);
            $phpCsFile->fixer->addContentBefore($position, ' * @method ' . $missingAnnotation);
        }
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param $stackPointer
     *
     * @return array
     */
    private function parseUseStatements(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        if (count($this->useStatements) === 0) {
            $tokens = $phpCsFile->getTokens();
            if ($phpCsFile->findPrevious(T_USE, $stackPointer)) {
                $position = $phpCsFile->findPrevious(T_USE, $stackPointer);
                while ($position !== false) {
                    $position = $phpCsFile->findPrevious(T_USE, $position);
                    if ($position !== false) {
                        $end = $phpCsFile->findEndOfStatement($position);
                        if ($tokens[$position]['type'] === 'T_USE') {
                            $useTokens = array_slice($tokens, $position + 2, $end - $position - 2);
                            $this->useStatements[] = $this->parseUseParts($useTokens);
                        }
                    }
                    $position--;
                }
            }
        }

        return $this->useStatements;
    }

    /**
     * @param array $useTokens
     *
     * @return string
     */
    private function parseUseParts(array $useTokens)
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
    private function addMissingUse(\PHP_CodeSniffer_File $phpCsFile, $stackPointer, $missingUse)
    {
        $previousUsePosition = $phpCsFile->findPrevious(T_USE, $stackPointer);
        if ($previousUsePosition !== false) {
            $endOfLastUse = $phpCsFile->findEndOfStatement($previousUsePosition);

            $phpCsFile->fixer->addNewline($endOfLastUse);
            $phpCsFile->fixer->addContent($endOfLastUse, 'use ' . $missingUse . ';');
        }
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    private function hasQueryContainerAnnotation(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $position = $phpCsFile->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $stackPointer);
        $tokens = $phpCsFile->getTokens();

        while ($position !== false) {
            $position = $phpCsFile->findPrevious(T_DOC_COMMENT_TAG, $position);
            if ($position !== false) {
                if (strpos($tokens[$position + 2]['content'], 'getQueryContainer()') !== false) {
                    return true;
                }
                $position--;
            }
        }

        return false;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    private function hasConfigAnnotation(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $position = $phpCsFile->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $stackPointer);
        $tokens = $phpCsFile->getTokens();

        while ($position !== false) {
            $position = $phpCsFile->findPrevious(T_DOC_COMMENT_TAG, $position);
            if ($position !== false) {
                if (strpos($tokens[$position + 2]['content'], 'getConfig()') !== false) {
                    return true;
                }
                $position--;
            }
        }

        return false;
    }

}
