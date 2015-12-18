<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Factory;

class QueryContainerMethodAnnotationSniff extends AbstractFactoryMethodAnnotationSniff
{

    const LAYER_PERSISTENCE = 'Persistence';

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        if (!$this->isFactory($phpCsFile)) {
            return;
        }

        $bundle = $this->getBundle($phpCsFile);
        $queryContainerName = $bundle . 'QueryContainer';

        if (!$this->hasQueryContainerAnnotation($phpCsFile, $stackPointer)
            && $this->fileExists($phpCsFile, $this->getQueryContainerClassName($phpCsFile))
        ) {
            $fix = $phpCsFile->addFixableError('getQueryContainer() annotation missing', $stackPointer);
            if ($fix) {
                $this->addQueryContainerAnnotation($phpCsFile, $stackPointer, $queryContainerName);
            }
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
     * @param $stackPointer
     * @param $queryContainerName
     *
     * @return void
     */
    private function addQueryContainerAnnotation(\PHP_CodeSniffer_File $phpCsFile, $stackPointer, $queryContainerName)
    {
        $phpCsFile->fixer->beginChangeset();

        if ($this->getLayer($phpCsFile) !== self::LAYER_PERSISTENCE) {
            $this->addUseStatements(
                $phpCsFile,
                $stackPointer,
                [$this->getQueryContainerClassName($phpCsFile)]
            );
        }

        if (!$this->hasDocBlock($phpCsFile, $stackPointer)) {
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, ' */');
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, ' * @method ' . $queryContainerName . ' getQueryContainer()');
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, '/**');
        } else {
            $position = $phpCsFile->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $stackPointer);
            $phpCsFile->fixer->addNewlineBefore($position);
            $phpCsFile->fixer->addContentBefore($position, ' * @method ' . $queryContainerName . ' getQueryContainer()');
        }

        $phpCsFile->fixer->endChangeset();
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
        array_push($classNameParts, self::LAYER_PERSISTENCE);
        array_push($classNameParts, $bundleName . 'QueryContainer');
        $queryContainerClassName = implode('\\', $classNameParts);

        return $queryContainerClassName;
    }

}
