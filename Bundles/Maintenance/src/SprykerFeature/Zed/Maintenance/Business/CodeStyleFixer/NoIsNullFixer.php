<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer;

use Symfony\CS\AbstractFixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

/**
 * Fixer NoIsNull
 *
 * @author Mark Scherer
 */
class NoIsNullFixer extends AbstractFixer
{

    const STRING_MATCH = 'is_null';

    /**
     * @see http://php.net/manual/en/language.operators.precedence.php
     *
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return string
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);
        $wrongTokens = [T_FUNCTION, T_OBJECT_OPERATOR];

        /** @var Token $token */
        foreach ($tokens as $index => $token) {
            $tokenContent = $token->getContent();
            if (empty($tokenContent) || $tokenContent !== self::STRING_MATCH) {
                continue;
            }

            $prevIndex = $tokens->getPrevNonWhitespace($index);
            if (in_array($tokens[$prevIndex]->getId(), $wrongTokens, true)) {
                continue;
            }

            $needsBrackets = false;

            if ($tokens[$prevIndex]->isCast()) {
                $needsBrackets = true;
            }

            $negated = false;
            if ($tokens[$prevIndex]->getContent() === '!') {
                $negated = true;
            }

            $nextIndex = $tokens->getNextMeaningfulToken($index);
            if ($tokens[$nextIndex]->getContent() !== '(') {
                continue;
            }

            $lastIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $nextIndex);

            $replacement = '';
            for ($i = $nextIndex + 1; $i < $lastIndex; ++$i) {
                // We should only change trivial cases to avoid changing code behavior
                if (!$tokens[$i]->isGivenKind([T_VARIABLE])) {
                    continue 2;
                }

                $replacement .= $tokens[$i]->getContent();
            }

            $replacement .= ' ' . ($negated ? '!' : '=') . '== null';
            if ($needsBrackets) {
                $replacement = '(' . $replacement . ')';
            }

            if ($negated) {
                $index -= $index - $prevIndex;
            }
            for ($i = $index; $i < $lastIndex; ++$i) {
                $tokens[$i]->clear();
            }
            $tokens[$lastIndex]->setContent($replacement);
        }

        return $tokens->generateCode();
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
     * @return string
     */
    public function getDescription()
    {
        return 'Always use strict null check instead if is_null() method invocation.';
    }

}
