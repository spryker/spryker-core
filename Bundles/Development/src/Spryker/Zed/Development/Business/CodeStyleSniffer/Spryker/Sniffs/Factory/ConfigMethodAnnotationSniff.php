<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Factory;

class ConfigMethodAnnotationSniff extends AbstractFactoryMethodAnnotationSniff
{

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
        $configName = $bundle . 'Config';

        if (!$this->hasConfigAnnotation($phpCsFile, $stackPointer) && $this->fileExists($phpCsFile, $this->getConfigClassName($phpCsFile))) {
            $fix = $phpCsFile->addFixableError('getConfig() annotation missing', $stackPointer);
            if ($fix) {
                $this->addConfigAnnotation($phpCsFile, $stackPointer, $configName);
            }
        }
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

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param $stackPointer
     * @param $configName
     *
     * @return void
     */
    private function addConfigAnnotation(\PHP_CodeSniffer_File $phpCsFile, $stackPointer, $configName)
    {
        $phpCsFile->fixer->beginChangeset();

        $this->addUseStatements(
            $phpCsFile,
            $stackPointer,
            [$this->getConfigClassName($phpCsFile)]
        );

        if (!$this->hasDocBlock($phpCsFile, $stackPointer)) {
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, ' */');
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, ' * @method ' . $configName . ' getConfig()');
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, '/**');
        } else {
            $position = $phpCsFile->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $stackPointer);
            $phpCsFile->fixer->addNewlineBefore($position);
            $phpCsFile->fixer->addContentBefore($position, ' * @method ' . $configName . ' getConfig()');
        }

        $phpCsFile->fixer->endChangeset();
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

}
