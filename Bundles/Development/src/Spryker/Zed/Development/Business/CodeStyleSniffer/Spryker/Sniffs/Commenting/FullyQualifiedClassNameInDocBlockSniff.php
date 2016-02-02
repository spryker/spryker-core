<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Commenting;

class FullyQualifiedClassNameInDocBlockSniff implements \PHP_CodeSniffer_Sniff
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
        $docBlockEndIndex = $this->findDocBlockEndForFunction($phpCsFile, $stackPointer);
        if (!$docBlockEndIndex) {
            return;
        }

        $tokens = $phpCsFile->getTokens();

        $docBlockStartIndex = $tokens[$docBlockEndIndex]['comment_opener'];

        for ($i = $docBlockStartIndex + 1; $i < $docBlockEndIndex; $i++) {
            if ($tokens[$i]['type'] !== 'T_DOC_COMMENT_TAG') {
                continue;
            }
            if (!in_array($tokens[$i]['content'], ['@return', '@param', '@throws'])) {
                continue;
            }

            $classNameIndex = $i + 2;

            if ($tokens[$classNameIndex]['type'] !== 'T_DOC_COMMENT_STRING') {
                continue;
            }

            $className = $tokens[$classNameIndex]['content'];

            // Fix a Sniffer bug with param having the variable also part of the content
            $appendix = '';
            if ($tokens[$i]['content'] === '@param') {
                $spaceIndex = strpos($className, ' ');

                $appendix = substr($className, $spaceIndex);
                $className = substr($className, 0, $spaceIndex);
            }

            if (strpos($className, '\\') !== false) {
                continue;
            }

            $useStatement = $this->findUseStatementForClassName($phpCsFile, $className);
            if (!$useStatement) {
                continue;
            }

            $fix = $phpCsFile->addFixableError($className . ' => ' . $useStatement, $classNameIndex);
            if ($fix) {
                $phpCsFile->fixer->replaceToken($classNameIndex, $useStatement . $appendix);
            }
        }
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param string $className
     *
     * @return string|null
     */
    protected function findUseStatementForClassName(\PHP_CodeSniffer_File $phpCsFile, $className)
    {
        $useStatements = $this->parseUseStatements($phpCsFile);

        if (!isset($useStatements[$className])) {
            return null;
        }

        return $useStatements[$className];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return int|null Stackpointer value of docblock end tag, or null if cannot be found
     */
    protected function findDocBlockEndForFunction(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $tokens = $phpCsFile->getTokens();

        $line = $tokens[$stackPointer]['line'];
        $beginningOfLine = $stackPointer;
        while ($tokens[$beginningOfLine - 1]['line'] === $line) {
            $beginningOfLine--;
        }

        if ($tokens[$beginningOfLine - 2]['type'] === 'T_DOC_COMMENT_CLOSE_TAG') {
            return $beginningOfLine - 2;
        }

        return null;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return array
     */
    protected function parseUseStatements(\PHP_CodeSniffer_File $phpCsFile)
    {
        $useStatements = [];
        $tokens = $phpCsFile->getTokens();

        foreach ($tokens as $id => $token) {
            if ($token['type'] !== 'T_USE') {
                continue;
            }

            $endIndex = $phpCsFile->findEndOfStatement($id);
            $useStatement = '';
            for ($i = $id + 2; $i < $endIndex; $i++) {
                $useStatement .= $tokens[$i]['content'];
            }

            $useStatement = trim($useStatement);

            if (strpos($useStatement, ' as ') !== false) {
                list($useStatement, $className) = explode(' as ', $useStatement);
            } else {
                $lastSeparator = strrpos($useStatement, '\\');
                $className = substr($useStatement, $lastSeparator + 1);
            }

            $useStatement = '\\' . $useStatement;

            $useStatements[$className] = $useStatement;
        }

        return $useStatements;
    }

}
