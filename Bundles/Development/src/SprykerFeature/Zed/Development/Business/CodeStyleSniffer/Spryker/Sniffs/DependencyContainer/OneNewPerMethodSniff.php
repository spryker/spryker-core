<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\DependencyContainer;

class OneNewPerMethodSniff implements \PHP_CodeSniffer_Sniff
{

    /**
     * @return array
     */
    public function register()
    {
        return [
            T_FUNCTION,
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
        if ($this->isDependencyContainer($phpCsFile) && $this->hasMoreThenOneNewInMethod($phpCsFile, $stackPointer)) {
            $classMethod = $this->getClassMethod($phpCsFile, $stackPointer);
            $phpCsFile->addError(
                $classMethod . ' contains more then one new. Fix this by extract a method.', $stackPointer
            );
        }
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
     *
     * @return string
     */
    protected function getMethodName(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $tokens = $phpCsFile->getTokens();
        $methodNamePosition = $phpCsFile->findNext(T_STRING, $stackPointer);
        $methodName = $tokens[$methodNamePosition]['content'];

        return $methodName;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return bool
     */
    protected function isDependencyContainer(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);

        return (substr($className, -19) === 'DependencyContainer');
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    protected function hasMoreThenOneNewInMethod(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $openPointer = $phpCsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPointer);
        $closePointer = $phpCsFile->findNext(T_CLOSE_CURLY_BRACKET, $openPointer);

        $firstNewPosition = $phpCsFile->findNext(T_NEW, $openPointer, $closePointer);
        $secondNewPosition = $phpCsFile->findNext(T_NEW, $firstNewPosition + 1, $closePointer);

        return $firstNewPosition > 0 && $secondNewPosition > 0;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return string
     */
    protected function getClassMethod(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $className = $this->getClassName($phpCsFile);
        $methodName = $this->getMethodName($phpCsFile, $stackPointer);

        $classMethod = $className . '::' . $methodName;

        return $classMethod;
    }

}
