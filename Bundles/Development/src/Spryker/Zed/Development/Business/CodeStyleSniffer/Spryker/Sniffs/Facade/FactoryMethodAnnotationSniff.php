<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Facade;

class FactoryMethodAnnotationSniff extends AbstractFacadeMethodAnnotationSniff
{

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        if (!$this->isFacade($phpCsFile)) {
            return;
        }

        $bundle = $this->getBundle($phpCsFile);
        $factoryName = $bundle . 'BusinessFactory';

        if (!$this->hasFactoryAnnotation($phpCsFile, $stackPointer) && $this->fileExists($phpCsFile, $this->getFactoryClassName($phpCsFile))) {
            $fix = $phpCsFile->addFixableError('getFactory() annotation missing', $stackPointer);
            if ($fix) {
                $this->addFactoryAnnotation($phpCsFile, $stackPointer, $factoryName);
            }
        }
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    private function hasFactoryAnnotation(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $position = $phpCsFile->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $stackPointer);
        $tokens = $phpCsFile->getTokens();

        while ($position !== false) {
            $position = $phpCsFile->findPrevious(T_DOC_COMMENT_TAG, $position);
            if ($position !== false) {
                if (strpos($tokens[$position + 2]['content'], 'getFactory()') !== false) {
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
     * @param $factoryName
     *
     * @return void
     */
    private function addFactoryAnnotation(\PHP_CodeSniffer_File $phpCsFile, $stackPointer, $factoryName)
    {
        $phpCsFile->fixer->beginChangeset();

        if (!$this->hasDocBlock($phpCsFile, $stackPointer)) {
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, ' */');
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, ' * @method ' . $factoryName . ' getFactory()');
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, '/**');
        } else {
            $position = $phpCsFile->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $stackPointer);
            $phpCsFile->fixer->addNewlineBefore($position);
            $phpCsFile->fixer->addContentBefore($position, ' * @method ' . $factoryName . ' getConfig()');
        }

        $phpCsFile->fixer->endChangeset();
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return array
     */
    private function getFactoryClassName(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);
        $classNameParts = explode('\\', $className);
        array_pop($classNameParts);
        $bundleName = $classNameParts[2];
        array_push($classNameParts, $bundleName . 'BusinessFactory');
        $factoryClassName = implode('\\', $classNameParts);

        return $factoryClassName;
    }

}
