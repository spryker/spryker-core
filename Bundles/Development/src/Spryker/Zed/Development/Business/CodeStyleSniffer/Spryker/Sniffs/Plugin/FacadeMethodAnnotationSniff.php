<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Plugin;

class FacadeMethodAnnotationSniff extends AbstractPluginMethodAnnotationSniff
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
        $facadeName = $bundle . 'Facade';

        if (!$this->hasFacadeAnnotation($phpCsFile, $stackPointer)
            && $this->fileExists($phpCsFile, $this->getFacadeClassName($phpCsFile))
        ) {
            $fix = $phpCsFile->addFixableError('getFacade() annotation missing', $stackPointer);
            if ($fix) {
                $this->addFacadeAnnotation($phpCsFile, $stackPointer, $facadeName);
            }
        }
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    private function hasFacadeAnnotation(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $position = $phpCsFile->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $stackPointer);
        $tokens = $phpCsFile->getTokens();

        while ($position !== false) {
            $position = $phpCsFile->findPrevious(T_DOC_COMMENT_TAG, $position);
            if ($position !== false) {
                if (strpos($tokens[$position + 2]['content'], 'getFacade()') !== false) {
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
     * @param string $facadeName
     *
     * @return void
     */
    private function addFacadeAnnotation(\PHP_CodeSniffer_File $phpCsFile, $stackPointer, $facadeName)
    {
        $phpCsFile->fixer->beginChangeset();

        $this->addUseStatements(
            $phpCsFile,
            $stackPointer,
            [$this->getFacadeClassName($phpCsFile)]
        );

        $stackPointer = $this->getStackPointerOfClassBegin($phpCsFile, $stackPointer);

        if (!$this->hasDocBlock($phpCsFile, $stackPointer)) {
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, ' */');
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, ' * @method ' . $facadeName . ' getFacade()');
            $phpCsFile->fixer->addNewlineBefore($stackPointer);
            $phpCsFile->fixer->addContentBefore($stackPointer, '/**');
        } else {
            $position = $phpCsFile->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $stackPointer);
            $phpCsFile->fixer->addNewlineBefore($position);
            $phpCsFile->fixer->addContentBefore($position, ' * @method ' . $facadeName . ' getFacade()');
        }

        $phpCsFile->fixer->endChangeset();
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return array
     */
    private function getFacadeClassName(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);
        $classNameParts = explode('\\', $className);
        $classNameParts = array_slice($classNameParts, 0, 3);
        $bundleName = $classNameParts[2];
        array_push($classNameParts, 'Business');
        array_push($classNameParts, $bundleName . 'Facade');
        $facadeClassName = implode('\\', $classNameParts);

        return $facadeClassName;
    }

}
