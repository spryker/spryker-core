<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business\CodeStyleFixer;

use Symfony\CS\AbstractFixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

class SprykerUseStatementFixer extends AbstractFixer
{

    /**
     * @var array
     */
    protected static $whiteListOfNamespaces = [
        'Pyz',
        'Orm',
        'Generated',
        'SprykerEngine',
        'SprykerFeature',
    ];

    /**
     * @var array
     */
    protected $existingStatements = [];

    /**
     * @var array
     */
    protected $newStatements = [];

    /**
     * @var array
     */
    protected $allStatements = [];

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Spryker internal FQNS must be moved to use statements.';
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        // Should be run before other UseFixer
        return 10;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return FixerInterface::NONE_LEVEL;
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        $namespaceStatements = $this->getNamespaceStatements($tokens);
        if (empty($namespaceStatements)) {
            return $tokens->generateCode();
        }

        $this->loadStatements($tokens);

        $this->fixUseForNew($tokens);
        $this->fixUseForStatic($tokens);

        $this->insertNewUseStatements($tokens, $namespaceStatements);

        return $tokens->generateCode();
    }

    /**
     * @param Tokens|Token[] $tokens
     *
     * @return void
     */
    protected function fixUseForStatic(Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind([T_DOUBLE_COLON])) {
                continue;
            }

            //TODO
        }
    }

    /**
     * @param Tokens|Token[] $tokens
     *
     * @return void
     */
    protected function fixUseForNew(Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind([T_NEW])) {
                continue;
            }

            $nextIndex = $tokens->getNextMeaningfulToken($index);
            $lastIndex = null;
            $i = $nextIndex;
            $extractedUseStatement = '';
            $lastSeparatorIndex = null;
            while (true) {
                if (!$tokens[$i]->isGivenKind([T_NS_SEPARATOR, T_STRING])) {
                    break;
                }
                $lastIndex = $i;
                $extractedUseStatement .= $tokens[$i]->getContent();
                if ($tokens[$i]->isGivenKind([T_NS_SEPARATOR])) {
                    $lastSeparatorIndex = $i;
                }
                ++$i;
            }

            if ($lastIndex === null || $lastSeparatorIndex === null) {
                continue;
            }

            $extractedUseStatement = ltrim($extractedUseStatement, '\\');
            if (!$this->isValidNamespace($extractedUseStatement)) {
                continue;
            }

            $name = '';
            for ($i = $lastSeparatorIndex + 1; $i <= $lastIndex; ++$i) {
                $name .= $tokens[$i]->getContent();
            }
            $addedUseStatement = $this->addUseStatement($name, $extractedUseStatement);
            if (!$addedUseStatement) {
                return;
            }

            for ($i = $nextIndex; $i <= $lastSeparatorIndex; ++$i) {
                $tokens[$i]->clear();
            }

            if ($addedUseStatement['aliased'] !== null) {
                $tokens[$lastSeparatorIndex + 1]->setContent($addedUseStatement['aliased']);
                for ($i = $lastSeparatorIndex + 2; $i <= $lastIndex; ++$i) {
                    $tokens[$i]->clear();
                }
            }

            if ($nextIndex === $index + 1) {
                $tokens[$index]->setContent($tokens[$index]->getContent() . ' ');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(\SplFileInfo $file)
    {
        // Possible Blacklist
        if (strpos($file, DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR) !== false) {
            return false;
        }

        return true;
    }

    /**
     * @param Tokens|Token[] $tokens
     *
     * @return array
     */
    protected function getNamespaceStatements(Tokens $tokens)
    {
        $namespaces = [];

        foreach ($tokens as $index => $token) {
            if (T_NAMESPACE !== $token->getId()) {
                continue;
            }

            $statementEndIndex = $tokens->getNextTokenOfKind($index, [';', '{']);

            $namespaces[] = [
                'end' => $statementEndIndex,
                'name' => trim($tokens->generatePartialCode($index + 1, $statementEndIndex - 1)),
                'start' => $index,
            ];
        }

        return $namespaces;
    }

    /**
     * @param Tokens|Token[] $tokens
     * @param array $useIndexes
     *
     * @return array
     */
    protected function getNamespaceUseStatements(Tokens $tokens, array $useIndexes)
    {
        $uses = [];

        foreach ($useIndexes as $index) {
            $statementEndIndex = $tokens->getNextTokenOfKind($index, [';']);
            $statementContent = $tokens->generatePartialCode($index + 1, $statementEndIndex - 1);

            // ignore multiple use statements like: `use BarB, BarC as C, BarD;`
            // that should be split into few separate statements
            if (strpos($statementContent, ',') !== false) {
                continue;
            }

            $statementParts = preg_split('/\s+as\s+/i', $statementContent);

            if (count($statementParts) === 1) {
                $fullName = $statementContent;
                $statementParts = explode('\\', $fullName);
                $shortName = end($statementParts);
                $aliased = null;
            } else {
                $fullName = $statementParts[0];
                $shortName = $statementParts[1];
                $statementParts = explode('\\', $fullName);
                $aliased = $shortName !== end($statementParts);
            }

            $shortName = trim($shortName);
            $fullName = trim($fullName);
            $key = $aliased ?: $shortName;

            $uses[$key] = [
                'aliased' => $aliased,
                'end' => $statementEndIndex,
                'fullName' => $fullName,
                'shortName' => $shortName,
                'start' => $index,
            ];
        }

        return $uses;
    }

    /**
     * @param Tokens|Token[] $tokens
     * @param array $namespaceStatements
     *
     * @return void
     */
    protected function insertNewUseStatements(Tokens $tokens, array $namespaceStatements)
    {
        $newStatements = $this->newStatements;
        $existingStatements = $this->existingStatements;

        if (empty($existingStatements)) {
            $useStatementStartIndex = $tokens->getNextMeaningfulToken($namespaceStatements[0]['end']);
            $tokens[$useStatementStartIndex]->setContent(PHP_EOL . $tokens[$useStatementStartIndex]->getContent());
        } else {
            $useStatementStartIndex = null;
            foreach ($existingStatements as $existingStatement) {
                $useStatementStartIndex = $existingStatement['start'];
                break;
            }
        }

        foreach ($newStatements as $alias => $useStatement) {
            if (!isset($existingStatements[$alias])) {
                $this->insertUseStatement($tokens, $useStatement, $useStatementStartIndex);
            }
        }
    }

    /**
     * @param Tokens|Token[] $tokens
     * @param array $useStatement
     * @param int $useStatementStartIndex
     *
     * @return void
     */
    protected function insertUseStatement(Tokens $tokens, array $useStatement, $useStatementStartIndex)
    {
        $alias = '';
        if (!empty($useStatement['aliased'])) {
            $alias = ' as ' . $useStatement['aliased'];
        }

        $content = 'use ' . $useStatement['fullName'] . $alias . ';';
        $content .= PHP_EOL;

        $tokens[$useStatementStartIndex]->override([T_STRING, $content . $tokens[$useStatementStartIndex]->getContent(), $tokens[$useStatementStartIndex]->getLine()]);
    }

    /**
     * @param string $extractedUseStatement
     *
     * @return bool
     */
    protected function isValidNamespace($extractedUseStatement)
    {
        $firstSeparator = mb_strpos($extractedUseStatement, '\\');
        $namespace = mb_substr($extractedUseStatement, 0, $firstSeparator);

        return in_array($namespace, self::$whiteListOfNamespaces);
    }

    /**
     * @param string $fullName
     * @param string $shortName
     *
     * @return array
     */
    protected function addUseStatement($shortName, $fullName)
    {
        // Find existing one
        foreach ($this->allStatements as $useStatement) {
            if ($useStatement['fullName'] === $fullName) {
                return $useStatement;
            }
        }

        $alias = $this->generateUniqueAlias($shortName, $fullName);
        if (!$alias) {
            return [];
        }

        $result = [
            'aliased' => $alias === $shortName ? null : $alias,
            'fullName' => $fullName,
            'shortName' => $shortName,
        ];
        $this->allStatements[$alias] = $result;
        $this->newStatements[$alias] = $result;

        return $result;
    }

    /**
     * @param $shortName
     * @param $fullName
     *
     * @return string|null
     */
    protected function generateUniqueAlias($shortName, $fullName)
    {
        $alias = $shortName;
        $count = 0;
        $pieces = explode('\\', $fullName);
        $pieces = array_reverse($pieces);
        array_shift($pieces);

        while (isset($this->allStatements[$alias])) {
            $alias = $shortName;

            if (count($pieces) - 1 < $count) {
                return null;
            }

            $prefix = '';
            for ($i = 0; $i <= $count; ++$i) {
                $prefix .= $pieces[$count];
            }

            $alias = $prefix . $alias;

            $count++;
        }

        return $alias;
    }

    /**
     * @param Tokens|Token[] $tokens
     *
     * @return void
     */
    protected function loadStatements($tokens)
    {
        $useStatementsIndexes = $tokens->getImportUseIndexes();
        $existingStatements = $this->getNamespaceUseStatements($tokens, $useStatementsIndexes);
        $this->existingStatements = $existingStatements;
        $this->allStatements = $existingStatements;
        $this->newStatements = [];
    }

}
