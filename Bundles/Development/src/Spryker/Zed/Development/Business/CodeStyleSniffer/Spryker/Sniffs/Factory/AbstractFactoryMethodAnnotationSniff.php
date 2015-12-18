<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Factory;

abstract class AbstractFactoryMethodAnnotationSniff implements \PHP_CodeSniffer_Sniff
{

    /**
     * @var array
     */
    protected $useStatements = [];

    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    protected $layer;

    /**
     * @var string
     */
    protected $fileExists = false;

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
    protected function getBundle(\PHP_CodeSniffer_File $phpCsFile)
    {
        if ($this->bundle === null) {
            $className = $this->getClassName($phpCsFile);
            $classNameParts = explode('\\', $className);
            $classNameParts = array_slice($classNameParts, 0, -2);

            $this->bundle = $classNameParts[2];
        }

        return $this->bundle;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return string
     */
    protected function getLayer(\PHP_CodeSniffer_File $phpCsFile)
    {
        if ($this->layer === null) {
            $className = $this->getClassName($phpCsFile);
            $classNameParts = explode('\\', $className);
            $this->layer =  $classNameParts[3];
        }

        return $this->layer;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    protected function hasDocBlock(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $tokens = $phpCsFile->getTokens();

        return ($tokens[$stackPointer - 2]['type'] === 'T_DOC_COMMENT_CLOSE_TAG');
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
     * @param $className
     *
     * @return bool
     */
    protected function fileExists(\PHP_CodeSniffer_File $phpCsFile, $className)
    {
        $fileName = $phpCsFile->getFilename();
        $fileNameParts = explode(DIRECTORY_SEPARATOR, $fileName);
        $sourceDirectoryPosition = array_search('src', $fileNameParts);
        $basePathParts = array_slice($fileNameParts, 0, $sourceDirectoryPosition + 1);

        $basePath = implode(DIRECTORY_SEPARATOR, $basePathParts) . DIRECTORY_SEPARATOR;
        $classFileName = str_replace('\\', DIRECTORY_SEPARATOR, $className);

        $fileName = $basePath . $classFileName . '.php';

        return file_exists($fileName);
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
