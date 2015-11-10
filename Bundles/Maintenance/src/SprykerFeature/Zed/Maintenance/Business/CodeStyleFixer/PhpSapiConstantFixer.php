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
 * Fixer PhpSapiConstant
 */
class PhpSapiConstantFixer extends AbstractFixer
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
        $wrongTokens = [T_FUNCTION, T_OBJECT_OPERATOR];

        foreach ($tokens as $index => $token) {
            $tokenContent = $token->getContent();
            if (strtolower($tokenContent) !== 'php_sapi_name') {
                continue;
            }

            $openingBrace = $tokens->getNextMeaningfulToken($index);
            if ($openingBrace === null || $tokens[$openingBrace]->getContent() !== '(') {
                continue;
            }

            $closingBrace = $tokens->getNextMeaningfulToken($openingBrace);
            if ($closingBrace === null || $tokens[$closingBrace]->getContent() !== ')') {
                continue;
            }

            $prevIndex = $tokens->getPrevNonWhitespace($index);
            if ($prevIndex === null || in_array($tokens[$prevIndex]->getId(), $wrongTokens, true)) {
                continue;
            }

            $tokens[$index]->setContent('PHP_SAPI');
            for ($i = $openingBrace; $i <= $closingBrace; ++$i) {
                $tokens[$i]->clear();
            }
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
        return 'Always use the simple constant for checking PHP_SAPI as invoking a method is slower.';
    }

}
