<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer;

use Symfony\CS\AbstractFixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\Tokenizer\Tokens;

/**
 * Fixer MethodArgumentDefaultValue
 *
 * @author Mark Scherer
 */
class MethodArgumentDefaultValueFixer extends AbstractFixer
{

    /**
     * @var bool
     */
    protected $defaultValueForbidden = false;

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'In method arguments there must not be arguments with default values before non-default ones.';
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            $token = $tokens[$index];

            // looking for start of brace and skip array
            if (!$token->equals('(') || $tokens[$index - 1]->isGivenKind(T_ARRAY)) {
                continue;
            }

            $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $index);

            $this->defaultValueForbidden = false;

            // Fix from last to first argument
            for ($i = $endIndex - 1; $i > $index; --$i) {
                if (!$tokens[$i]->equals(',')) {
                    continue;
                }

                $this->fixDefaultValues($tokens, $i, $endIndex - 1);
            }

            $this->fixDefaultValues($tokens, $i, $endIndex - 1);
        }

        return $tokens->generateCode();
    }

    /**
     * Method to remove incorrect default values.
     *
     * @param Tokens $tokens
     * @param int $index
     * @param int $endIndex
     *
     * @return void
     */
    protected function fixDefaultValues(Tokens $tokens, $index, $endIndex)
    {
        $hasDefaultValue = false;
        for ($i = $endIndex; $i > $index; $i--) {
            if ($tokens[$i]->equals('=')) {
                $hasDefaultValue = true;
                break;
            }
        }

        if (!$hasDefaultValue) {
            $this->defaultValueForbidden = true;

            return;
        }

        if ($hasDefaultValue && !$this->defaultValueForbidden) {
            return;
        }

        $positionOfEqualSign = $i;

        // Remove wrong default value
        for ($i = $positionOfEqualSign; $i <= $endIndex; $i++) {
            if ($tokens[$i]->equals(',')) {
                break;
            }
            $tokens[$i]->clear();
        }

        // Also remove whitespace then before the equal sign
        $prevIndex = $tokens->getPrevNonWhitespace($positionOfEqualSign);
        for ($i = $positionOfEqualSign - 1; $i > $prevIndex; $i--) {
            $tokens[$i]->clear();
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

}
