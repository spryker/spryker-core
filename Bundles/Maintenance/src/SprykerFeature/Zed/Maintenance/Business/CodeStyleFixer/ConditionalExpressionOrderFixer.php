<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer;

use Symfony\CS\AbstractFixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

if (!defined('T_GREATER_THAN')) {
    define('T_GREATER_THAN', 1024);
}
if (!defined('T_LESS_THAN')) {
    define('T_LESS_THAN', 1025);
}

/**
 * Fixer ConditionalExpressionOrder
 *
 * @author Mark Scherer
 */

class ConditionalExpressionOrderFixer extends AbstractFixer
{

    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return string
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        $this->fixConditions($file, $tokens);

        return $tokens->generateCode();
    }

    /**
     * @see http://php.net/manual/en/language.operators.precedence.php
     *
     * @param \SplFileInfo $file
     * @param Tokens &$tokens
     *
     * @return void
     */
    protected function fixConditions(\SplFileInfo $file, &$tokens)
    {
        /** @var Token $token */
        foreach ($tokens as $index => $token) {
            if ($token->getContent() !== '(') {
                continue;
            }

            // Look for the first expression
            $leftIndex = $tokens->getNextMeaningfulToken($index);
            if (!$leftIndex || $tokens[$leftIndex]->getContent() === ')') {
                continue;
            }

            // Only sniff for specified tokens
            if (!$tokens[$leftIndex]->isNativeConstant()
                && !in_array($tokens[$leftIndex]->getId(), [T_LNUMBER, T_CONSTANT_ENCAPSED_STRING])
            ) {
                continue;
            }
            $leftIndexStart = $leftIndex;

            // Get the comparison operator
            $comparisonIndex = $tokens->getNextMeaningfulToken($leftIndex);

            // Fix incomplete token parsing
            if ($tokens[$comparisonIndex]->getContent() === '<') {
                $tokens[$comparisonIndex]->override([T_LESS_THAN, '<', $tokens[$comparisonIndex]->getLine()]);
            }
            if ($tokens[$comparisonIndex]->getContent() === '>') {
                $tokens[$comparisonIndex]->override([T_GREATER_THAN, '>', $tokens[$comparisonIndex]->getLine()]);
            }

            $tokensToCheck = [T_IS_IDENTICAL, T_IS_NOT_IDENTICAL, T_IS_EQUAL, T_IS_NOT_EQUAL, T_GREATER_THAN, T_LESS_THAN,
                T_IS_GREATER_OR_EQUAL, T_IS_SMALLER_OR_EQUAL, ];
            if (!in_array($tokens[$comparisonIndex]->getId(), $tokensToCheck)) {
                continue;
            }

            // Look for the right expression
            $rightIndex = $tokens->getNextMeaningfulToken($comparisonIndex);
            if (!$rightIndex) {
                $error = 'Usage of Yoda conditions is not advised. Please switch the expression order.';
                $file->addError($error, $comparisonIndex, 'ExpressionOrder');
                continue;
            }

            $rightIndexStart = $rightIndex;

            // If its T_OPEN_PARENTHESIS we need to find the closing one
            if ($tokens[$rightIndex]->getContent() === '(') {
                // skip for now
                continue;
            }

            // Check if we need to inverse comparison operator
            $comparisonIndexValue = $tokens[$comparisonIndex]->getContent();
            if (in_array($tokens[$comparisonIndex]->getId(), [T_GREATER_THAN, T_LESS_THAN,
                T_IS_GREATER_OR_EQUAL, T_IS_SMALLER_OR_EQUAL, ])) {
                $mapping = [
                    T_GREATER_THAN => '<',
                    T_LESS_THAN => '>',
                    T_IS_GREATER_OR_EQUAL => '<=',
                    T_IS_SMALLER_OR_EQUAL => '>=',
                ];
                $comparisonIndexValue = $mapping[$tokens[$comparisonIndex]->getId()];
            }

            $rightIndexEnd = $tokens->getNextMeaningfulToken($rightIndexStart);
            if (!$rightIndex || $tokens[$rightIndexEnd]->getContent() !== ')') {
                // skip for now
                continue;
            }

            // Fix the error
            $tmp = '';
            for ($i = $leftIndexStart; $i <= $leftIndex; $i++) {
                $tmp .= $tokens[$i]->getContent();
            }
            $rightIndexValue = $tokens[$rightIndex]->getContent();

            for ($i = $leftIndexStart; $i < $rightIndexStart; $i++) {
                $tokens[$i]->setContent('');
            }
            $tokens[$rightIndex]->setContent($rightIndexValue . ' ' . $comparisonIndexValue . ' ' . $tmp);
        }
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
        return 'Usage of Yoda conditions is not allowed. Switch the expression order.';
    }

}
