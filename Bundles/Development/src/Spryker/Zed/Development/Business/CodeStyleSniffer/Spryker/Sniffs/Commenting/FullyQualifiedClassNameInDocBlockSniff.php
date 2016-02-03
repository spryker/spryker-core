<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\Commenting;

class FullyQualifiedClassNameInDocBlockSniff implements \PHP_CodeSniffer_Sniff
{

    /**
     * @var array
     */
    public static $whitelistedTypes = [
        'string', 'int', 'integer', 'float', 'bool', 'boolean', 'resource', 'null', 'void', 'callable',
        'array', 'mixed', 'object', 'false', 'true', 'self', 'static', '$this',
    ];

    /**
     * @return array
     */
    public function register()
    {
        return [
            T_CLASS,
            T_INTERFACE,
            T_TRAIT,
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
            if (!in_array($tokens[$i]['content'], ['@return', '@param', '@throws', '@var', '@method'])) {
                continue;
            }

            $classNameIndex = $i + 2;

            if ($tokens[$classNameIndex]['type'] !== 'T_DOC_COMMENT_STRING') {
                continue;
            }

            $content = $tokens[$classNameIndex]['content'];

            $appendix = '';
            $spaceIndex = strpos($content, ' ');
            if ($spaceIndex) {
                $appendix = substr($content, $spaceIndex);
                $content = substr($content, 0, $spaceIndex);
            }

            if (empty($content)) {
                continue;
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
     *
     * @return void
     */
    protected function fixClassNames(\PHP_CodeSniffer_File $phpCsFile, $classNameIndex, array $classNames, $appendix)
    {
        $result = [];
        foreach ($classNames as $key => $className) {
            if (strpos($className, '\\') !== false) {
                continue;
            }

            $arrayOfObject = false;
            if (substr($className, -2) === '[]') {
                $arrayOfObject = true;
                $className = substr($className, 0, -2);
            }

            if (in_array($className, self::$whitelistedTypes)) {
                continue;
            }

            $useStatement = $this->findUseStatementForClassName($phpCsFile, $className);
            if (!$useStatement) {
                $phpCsFile->addError('Invalid class name "' . $className . '"', $classNameIndex);
                continue;
            }

            $classNames[$key] = $useStatement . ($arrayOfObject ? '[]' : '');
            $result[$className . ($arrayOfObject ? '[]' : '')] = $classNames[$key];
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
            $useStatement = $this->findInSameNameSpace($phpCsFile, $className);
            if ($useStatement) {
                return $useStatement;
            }

            return null;
        }

        return $useStatements[$className];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param string $className
     *
     * @return string|null
     */
    protected function findInSameNameSpace(\PHP_CodeSniffer_File $phpCsFile, $className)
    {
        $currentNameSpace = $this->getNamespace($phpCsFile);
        if (!$currentNameSpace) {
            return null;
        }

        $file = $phpCsFile->getFilename();
        $dir = dirname($file) . DIRECTORY_SEPARATOR;
        if (!file_exists($dir . $className . '.php')) {
            return null;
        }

        return '\\' . $currentNameSpace . '\\' . $className;
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return string
     */
    protected function getNamespace(\PHP_CodeSniffer_File $phpCsFile)
    {
        $tokens = $phpCsFile->getTokens();

        $nsStart = null;
        foreach ($tokens as $id => $token) {
            if ($token['code'] !== T_NAMESPACE) {
                continue;
            }

            $nsStart = $id + 1;
            break;
        }
        if (!$nsStart) {
            return '';
        }

        $nsEnd = $phpCsFile->findNext(
            [
                T_NS_SEPARATOR,
                T_STRING,
                T_WHITESPACE,
            ],
            $nsStart,
            null,
            true
        );

        $namespace = trim($phpCsFile->getTokensAsString(($nsStart), ($nsEnd - $nsStart)));

        return $namespace;
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
                $className = $useStatement;
                if (strpos($useStatement, '\\') !== false) {
                    $lastSeparator = strrpos($useStatement, '\\');
                    $className = substr($useStatement, $lastSeparator + 1);
                }
            }

            $useStatement = '\\' . ltrim($useStatement, '\\');

            $useStatements[$className] = $useStatement;
        }

        return $useStatements;
    }

}
