<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Plugin;

use Spryker\Sniffs\AbstractSniffs\AbstractMethodAnnotationSniff;

abstract class AbstractPluginMethodAnnotationSniff extends AbstractMethodAnnotationSniff
{

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    protected function isPlugin(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        if ($this->isFileInPluginDirectory($phpCsFile) && $this->extendsAbstractPlugin($phpCsFile, $stackPointer)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return int
     */
    private function isFileInPluginDirectory(\PHP_CodeSniffer_File $phpCsFile)
    {
        return preg_match('/Communication\/Plugin/', $phpCsFile->getFilename());
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    private function extendsAbstractPlugin(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $extendedClassName = $phpCsFile->findExtendedClassName($stackPointer);

        if ($extendedClassName === 'AbstractPlugin') {
            return true;
        }

        return false;
    }

}
