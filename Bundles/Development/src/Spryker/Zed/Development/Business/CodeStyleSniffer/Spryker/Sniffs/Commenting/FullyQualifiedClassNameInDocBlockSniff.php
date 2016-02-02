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
            T_VARIABLE,
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

        $docBlockEndIndex = $this->findRelatedDocBlock($phpCsFile, $stackPointer);

        if (!$docBlockEndIndex) {
            return;
        }

        $docBlockStartIndex = $tokens[$docBlockEndIndex]['comment_opener'];

        for ($i = $docBlockStartIndex + 1; $i < $docBlockEndIndex; $i++) {
            if ($tokens[$i]['type'] !== 'T_DOC_COMMENT_TAG') {
                continue;
            }
            if (!in_array($tokens[$i]['content'], ['@return', '@param', '@throws', '@var'])) {
                continue;
            }

            $classNameIndex = $i + 2;

            if ($tokens[$classNameIndex]['type'] !== 'T_DOC_COMMENT_STRING') {
                continue;
            }

            $content = $tokens[$classNameIndex]['content'];

            // Fix a Sniffer bug with param having the variable also part of the content
            $appendix = '';
            if ($tokens[$i]['content'] === '@param') {
                $spaceIndex = strpos($content, ' ');

                $appendix = substr($content, $spaceIndex);
                $content = substr($content, 0, $spaceIndex);
            }

            $classNames = explode('|', $content);
            $this->fixClassNames($phpCsFile, $classNameIndex, $classNames, $appendix);
        }
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $classNameIndex
     * @param array $classNames
     * @param string $appendix
     * @return void
     */
    protected function fixClassNames(\PHP_CodeSniffer_File $phpCsFile, $classNameIndex, array $classNames, $appendix)
    {
        $result = [];
        foreach ($classNames as $key => $className) {
            if (strpos($className, '\\') !== false) {
                continue;
            }

            $useStatement = $this->findUseStatementForClassName($phpCsFile, $className);
            if (!$useStatement) {
                continue;
            }

            $classNames[$key] = $useStatement;
            $result[$className] = $useStatement;
        }

        if (!$result) {
            return;
        }

        $message = [];
        foreach ($result as $className => $useStatement) {
            $message[] = $className . ' => ' . $useStatement;
        }

        $fix = $phpCsFile->addFixableError(implode(', ', $message), $classNameIndex);
        if ($fix) {
            $newContent = implode('|', $classNames);

            $phpCsFile->fixer->replaceToken($classNameIndex, $newContent . $appendix);
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
    protected function findRelatedDocBlock(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
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
