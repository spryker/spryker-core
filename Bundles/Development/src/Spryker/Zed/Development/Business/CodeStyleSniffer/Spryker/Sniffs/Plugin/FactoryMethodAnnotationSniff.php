<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Plugin;

class FactoryMethodAnnotationSniff extends AbstractPluginMethodAnnotationSniff
{

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        if (!$this->isPlugin($phpCsFile, $stackPointer)) {
            return;
        }

        $bundle = $this->getBundle($phpCsFile);
        $factoryName = $bundle . 'CommunicationFactory';
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
     * @param int $stackPointer
     * @param string $factoryName
     *
     * @return void
     */
    private function addFactoryAnnotation(\PHP_CodeSniffer_File $phpCsFile, $stackPointer, $factoryName)
    {
        $phpCsFile->fixer->beginChangeset();

        $this->addUseStatements(
            $phpCsFile,
            $stackPointer,
            [$this->getFactoryClassName($phpCsFile)]
        );

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
            $phpCsFile->fixer->addContentBefore($position, ' * @method ' . $factoryName . ' getFactory()');
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
        $classNameParts = array_slice($classNameParts, 0, 4);
        $bundleName = $classNameParts[2];
        array_push($classNameParts, $bundleName . 'CommunicationFactory');
        $factoryClassName = implode('\\', $classNameParts);

        return $factoryClassName;
    }

}
