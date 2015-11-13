<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer;

use Symfony\CS\AbstractFixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

class FunctionSpacingFixer extends AbstractFixer
{

    /**
     * Fixes a file.
     *
     * @param \SplFileInfo $file A \SplFileInfo instance
     * @param string $content The file content
     *
     * @return string The fixed file content
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        $this->fixContent($tokens);

        return $tokens->generateCode();
    }

    /**
     * @param Tokens|Token[] $tokens
     *
     * @return void
     */
    protected function fixContent(Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if ($token->isGivenKind([T_FUNCTION])) {
                $openingBraceIndex = $tokens->getNextTokenOfKind($index, ['{']);
                if ($openingBraceIndex === null) {
                    $openingBracketIndex = $tokens->getNextTokenOfKind($index, ['(']);
                    $closingBracketIndex = $tokens->getNextTokenOfKind($index, [')']);

                    $nextIndex = $tokens->getNextMeaningfulToken($closingBracketIndex);

                    if (!$this->isEmptyLineAfterIndex($tokens, $nextIndex)) {
                        $tokens[$nextIndex + 1]->setContent("\n" . $tokens[$nextIndex + 1]->getContent());
                    }

                    continue;
                }

                $closingBraceIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_CURLY_BRACE, $openingBraceIndex);

                // Ignore closures
                $nextIndex = $tokens->getNextMeaningfulToken($closingBraceIndex);
                if ($tokens[$nextIndex]->equals(';') || $tokens[$nextIndex]->equals(',') || $tokens[$nextIndex]->equals(')')) {
                    continue;
                }

                if (!$this->isEmptyLineAfterIndex($tokens, $closingBraceIndex)) {
                    $tokens[$closingBraceIndex + 1]->setContent("\n" . $tokens[$closingBraceIndex + 1]->getContent());
                }
            }
        }
    }

    /**
     * Returns the description of the fixer.
     *
     * A short one-line description of what the fixer does.
     *
     * @return string The description of the fixer
     */
    public function getDescription()
    {
        return 'There should be exactly one blank line before function start and after function end';
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return -100;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return FixerInterface::NONE_LEVEL;
    }

    /**
     * @param Tokens $tokens
     * @param int $openingBraceIndex
     *
     * @return bool
     */
    protected function isEmptyLineAfterIndex(Tokens $tokens, $openingBraceIndex)
    {
        return mb_substr_count($tokens[$openingBraceIndex + 1]->getContent(), "\n") >= 2;
    }

    /**
     * @param Tokens $tokens
     * @param int $closingBraceIndex
     *
     * @return bool
     */
    protected function isEmptyLineBeforeIndex(Tokens $tokens, $closingBraceIndex)
    {
        return mb_substr_count($tokens[$closingBraceIndex - 1]->getContent(), "\n") >= 2;
    }

}
