<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer;

use Symfony\CS\AbstractFixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

class RemoveWrongWhitespaceFixer extends AbstractFixer
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

        $this->fixWhitespaceAfterReturnToken($tokens);

        return $tokens->generateCode();
    }

    /**
     * @param Tokens|Token[] $tokens
     *
     * @return void
     */
    protected function fixWhitespaceAfterReturnToken(Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind([T_RETURN])) {
                continue;
            }

            $nextIndex = $tokens->getNextMeaningfulToken($index);

            if ($nextIndex <= $index + 1) {
                continue;
            }

            $whitespace = $tokens[$index + 1];
            $cleaned = preg_replace('/^[ ]+/', ' ', $whitespace->getContent());
            $whitespace->setContent($cleaned);
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
        return 'Fix whitespace after return statement and alike.';
    }

}
