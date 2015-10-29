<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer;

use Symfony\CS\AbstractFixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;
use Symfony\CS\Utils;

/**
 * Fixer NoInlineAssignment
 *
 * @author Mark Scherer
 */

class NoInlineAssignmentFixer extends AbstractFixer
{

    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            /* @var Token $token */
            $token = $tokens[$index];

            if (!$token->isGivenKind([T_FOREACH, T_FOR, T_WHILE, T_IF, T_SWITCH])) {
                continue;
            }
            $startIndex = $tokens->getNextMeaningfulToken($index);
            $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $startIndex);

            $hasInlineAssignment = false;
            $indexEqualSign = null;
            for ($i = $index; $i < $endIndex; $i++) {
                /* @var Token $currentToken */
                $currentToken = $tokens[$i];

                // We need to skip for complex assignments
                if ($currentToken->isGivenKind([T_BOOLEAN_OR, T_BOOLEAN_AND, T_LOGICAL_OR, T_LOGICAL_XOR, T_LOGICAL_AND])) {
                    $hasInlineAssignment = false;
                    break;
                }

                if (!$currentToken->equals('=')) {
                    continue;
                }

                $indexEqualSign = $i;
                $hasInlineAssignment = true;
            }

            if (!$hasInlineAssignment) {
                continue;
            }

            // Remove into own $var
            $string = '';
            $var = '';
            for ($i = $startIndex + 1; $i < $endIndex; ++$i) {
                $string .= $tokens[$i]->getContent();
                if ($i < $indexEqualSign) {
                    $var .= $tokens[$i]->getContent();
                }

                $tokens[$i]->clear();
            }

            $string .= ';';

            $tokens[$i - 1]->setContent(trim($var));

            $content = $tokens[$index]->getContent();
            $indent = Utils::calculateTrailingWhitespaceIndent($tokens[$index - 1]);
            $content = $indent . $content;

            $content = $string . PHP_EOL . $content;
            $tokens[$index]->setContent($content);
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
        return FixerInterface::CONTRIB_LEVEL;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Inline assignment is not allowed. Extract into an own line above.';
    }

}
