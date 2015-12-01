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

    protected static $namespaces = [
        'Pyz',
        'Orm',
        'Generated',
        'SprykerEngine',
        'SprykerFeature',
    ];

    protected $useStatements = [];

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

        $namespaceDeclarations = $this->getNamespaceDeclarations($tokens);
        if (empty($namespaceDeclarations)) {
            return $tokens->generateCode();
        }

        $this->useStatements = [];

        $this->fixUseForNew($tokens);
        $this->fixUseForStatic($tokens);

        $this->insertNewUseDeclarations($tokens, $namespaceDeclarations);

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
            $this->addUseStatement($extractedUseStatement, $name);

            for ($i = $nextIndex; $i <= $lastSeparatorIndex; ++$i) {
                $tokens[$i]->clear();
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

    protected function getNamespaceDeclarations(Tokens $tokens)
    {
        $namespaces = array();

        foreach ($tokens as $index => $token) {
            if (T_NAMESPACE !== $token->getId()) {
                continue;
            }

            $declarationEndIndex = $tokens->getNextTokenOfKind($index, array(';', '{'));

            $namespaces[] = array(
                'end' => $declarationEndIndex,
                'name' => trim($tokens->generatePartialCode($index + 1, $declarationEndIndex - 1)),
                'start' => $index,
            );
        }

        return $namespaces;
    }

    protected function getNamespaceUseDeclarations(Tokens $tokens, array $useIndexes)
    {
        $uses = array();

        foreach ($useIndexes as $index) {
            $declarationEndIndex = $tokens->getNextTokenOfKind($index, array(';'));
            $declarationContent = $tokens->generatePartialCode($index + 1, $declarationEndIndex - 1);

            // ignore multiple use statements like: `use BarB, BarC as C, BarD;`
            // that should be split into few separate statements
            if (false !== strpos($declarationContent, ',')) {
                continue;
            }

            $declarationParts = preg_split('/\s+as\s+/i', $declarationContent);

            if (count($declarationParts) === 1) {
                $fullName = $declarationContent;
                $declarationParts = explode('\\', $fullName);
                $shortName = end($declarationParts);
                $aliased = false;
            } else {
                $fullName = $declarationParts[0];
                $shortName = $declarationParts[1];
                $declarationParts = explode('\\', $fullName);
                $aliased = $shortName !== end($declarationParts);
            }

            $shortName = trim($shortName);
            $fullName = trim($fullName);

            $uses[$fullName] = array(
                'aliased' => $aliased,
                'end' => $declarationEndIndex,
                'fullName' => $fullName,
                'shortName' => $shortName,
                'start' => $index,
            );
        }

        return $uses;
    }

    /**
     * @param Tokens|Token[] $tokens
     * @param array $namespaceDeclarations
     *
     * @return void
     */
    protected function insertNewUseDeclarations(Tokens $tokens, array $namespaceDeclarations)
    {
        $useDeclarationsIndexes = $tokens->getImportUseIndexes();
        $existingDeclarations = $this->getNamespaceUseDeclarations($tokens, $useDeclarationsIndexes);

        $newDeclarations = $this->useStatements;

        if (empty($existingDeclarations)) {
            $useStatementStartIndex = $tokens->getNextMeaningfulToken($namespaceDeclarations[0]['end']);
            $tokens[$useStatementStartIndex]->setContent(PHP_EOL . $tokens[$useStatementStartIndex]->getContent());
        } else {
            $useStatementStartIndex = null;
            foreach ($existingDeclarations as $existingDeclaration) {
                $useStatementStartIndex = $existingDeclaration['start'];
                break;
            }
        }

        foreach ($newDeclarations as $fullName => $useDeclaration) {
            if (!isset($existingDeclarations[$fullName])) {
                $this->addUseDeclaration($tokens, $useDeclaration, $useStatementStartIndex);
            }
        }
    }

    /**
     * @param Tokens|Token[] $tokens
     * @param array $useDeclaration
     * @param int $useStatementStartIndex
     *
     * @return void
     */
    protected function addUseDeclaration(Tokens $tokens, array $useDeclaration, $useStatementStartIndex)
    {
        $content = 'use ' . $useDeclaration['fullName'] . ';';
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

        return in_array($namespace, self::$namespaces);
    }

    /**
     * @param string $fullName
     * @param string $shortName
     *
     * @return void
     */
    protected function addUseStatement($fullName, $shortName) {
       if (!in_array($shortName, $this->useStatements)) {
           $this->useStatements[$fullName] = [
               'fullName' => $fullName,
               'shortName' => $shortName,
           ];
       }
    }

}
