<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer;

use Symfony\CS\AbstractFixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

class EmptyEnclosingLinesFixer extends AbstractFixer
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

        foreach ($tokens as $index => $token) {
            /* @var Token $openingBrace */
            if ($token->isGivenKind([T_CLASS, T_INTERFACE, T_TRAIT])) {
                $openingBraceIndex = $tokens->getNextTokenOfKind($index, ['{']);
                $closingBraceIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_CURLY_BRACE, $openingBraceIndex);

                if (!$this->isEmptyLineAfterIndex($tokens, $openingBraceIndex)) {
                    if ($tokens[$openingBraceIndex + 2]->getContent() !== '}') {
                        $tokens[$openingBraceIndex + 1]->setContent("\n" . $tokens[$openingBraceIndex + 1]->getContent());
                    }
                }

                if (!$this->isEmptyLineBeforeIndex($tokens, $closingBraceIndex)) {
                    if ($tokens[$closingBraceIndex - 2]->getContent() !== '{') {
                        $tokens[$closingBraceIndex - 1]->setContent($tokens[$closingBraceIndex - 1]->getContent() . "\n");
                    }
                } else {
                    if ($openingBraceIndex + 2 === $closingBraceIndex) {
                        $tokens[$openingBraceIndex + 1]->setContent("\n");
                    }
                }
            }
        }

        return $tokens->generateCode();
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
        return 'There should be exactly one blank line after class start and before class end';
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
