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
 */
class NoIsNullFixer extends AbstractFixer
{

    const STRING_MATCH = 'is_null';

    protected $startIndex;

    protected $endIndex;

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
            if ($tokenContent !== self::STRING_MATCH) {
                continue;
            }

            $prevIndex = $tokens->getPrevNonWhitespace($index);
            if (in_array($tokens[$prevIndex]->getId(), $wrongTokens, true)) {
                continue;
            }

            $nextIndex = $tokens->getNextMeaningfulToken($index);
            if ($tokens[$nextIndex]->getContent() !== '(') {
                continue;
            }

            $lastIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $nextIndex);

            $needsBrackets = false;
            if ($tokens[$prevIndex]->isCast() || $tokens[$prevIndex]->isGivenKind([T_IS_NOT_EQUAL, T_IS_EQUAL, T_IS_IDENTICAL, T_IS_NOT_IDENTICAL])) {
                $needsBrackets = true;
            }

            $endBraceIndex = $tokens->getNextTokenOfKind($nextIndex, [')']);

            $nextEndBraceIndex = $tokens->getNextMeaningfulToken($endBraceIndex);
            if ($tokens[$nextEndBraceIndex]->isGivenKind([T_IS_NOT_EQUAL, T_IS_EQUAL, T_IS_IDENTICAL, T_IS_NOT_IDENTICAL])) {
                $needsBrackets = true;
            }

            // Special fix: true/false === is_null() => !==/=== null
            if ($this->isFixableComparison($tokens, $prevIndex, $nextEndBraceIndex)) {
                $needsBrackets = false;
            }

            $negated = false;
            if ($tokens[$prevIndex]->getContent() === '!') {
                $negated = true;
            }

            $replacement = '';
            for ($i = $nextIndex + 1; $i < $lastIndex; ++$i) {
                // We should only change trivial cases to avoid changing code behavior
                if (!$tokens[$i]->isGivenKind([T_VARIABLE])) {
                    continue 2;
                }

                $replacement .= $tokens[$i]->getContent();
            }

            if ($this->startIndex !== null) {
                $index = $this->startIndex;
                $this->endIndex = $lastIndex;
                $negated = $tokens[$this->startIndex]->getContent() === 'false' ? true : false;
                $needsBrackets = false;
            }

            if ($this->endIndex !== null) {
                $lastIndex = $this->endIndex;

                if ($this->startIndex !== null) {
                    $token = $tokens[$this->startIndex];
                } else {
                    $token = $tokens[$this->endIndex];
                }

                $negated = $token->getContent() === 'false' ? true : false;
                $needsBrackets = false;
            }

            $replacement .= ' ' . ($negated ? '!' : '=') . '== null';
            if ($needsBrackets) {
                $replacement = '(' . $replacement . ')';
            }

            $offset = 0;
            if ($negated && $this->startIndex === null && $this->endIndex === null) {
                $offset = -($index - $prevIndex);
            }

            $index += $offset;
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
        return -10;
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

    /**
     * @param Tokens $tokens
     * @param int $prevIndex
     * @param int $nextEndBraceIndex
     *
     * @return bool
     */
    protected function isFixableComparison($tokens, $prevIndex, $nextEndBraceIndex)
    {
        if ($tokens[$prevIndex]->isGivenKind([T_IS_NOT_IDENTICAL, T_IS_IDENTICAL])) {
            $prevPrevIndex = $tokens->getPrevMeaningfulToken($prevIndex);
            if ($tokens[$prevPrevIndex]->getContent() === 'true' || $tokens[$prevPrevIndex]->getContent() === 'false') {
                $this->startIndex = $prevPrevIndex;

                return true;
            }
        }

        if ($nextEndBraceIndex === null) {
            return false;
        }

        if ($tokens[$nextEndBraceIndex]->isGivenKind([T_IS_NOT_IDENTICAL, T_IS_IDENTICAL])) {
            $nextNextIndex = $tokens->getNextMeaningfulToken($nextEndBraceIndex);

            if ($tokens[$nextNextIndex]->getContent() === 'true' || $tokens[$nextNextIndex]->getContent() === 'false') {
                $this->endIndex = $nextNextIndex;

                return true;
            }
        }

        return false;
    }

}
