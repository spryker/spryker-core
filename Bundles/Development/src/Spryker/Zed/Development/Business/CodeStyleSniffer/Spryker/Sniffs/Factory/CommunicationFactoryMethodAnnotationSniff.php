<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Factory;

class CommunicationFactoryMethodAnnotationSniff implements \PHP_CodeSniffer_Sniff
{

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
        if ($this->isCommunicationFactory($phpCsFile)) {
            if ($this->hasDocBlock($stackPointer, $tokens)) {

                // doc block found check which elements are present
                return;
            }

            $fix = $phpCsFile->addFixableError('getQueryContainer() and getConfig() annotation missing', $stackPointer);
            if ($fix) {

                // add QueryContainer annotation
                // add Config annotation

                echo '<pre>' . PHP_EOL . var_dump('add missing annotations') . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
            }
        }
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return bool
     */
    protected function isCommunicationFactory(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);

        return (substr($className, -20) === 'CommunicationFactory');
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
        return ($tokens[$stackPointer - 1]['type'] === T_DOC_COMMENT_CLOSE_TAG);
    }

}
