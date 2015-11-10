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
 * Fixer ShortCast
 */
class ShortCastFixer extends AbstractFixer
{

    /**
     * @var array
     */
    public static $matching = [
        '(boolean)' => '(bool)',
        '(integer)' => '(int)',
    ];

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
        foreach ($tokens as $index => $token) {
            // Don't use !!
            if ($token->getContent() === '!' && $tokens[$index - 1]->getContent() === '!') {
                $tokens[$index - 1]->setContent('');
                $tokens[$index]->setContent('(bool)');
                continue;
            }

            // Don't use long form
            if ($token->isCast()) {
                $content = $token->getContent();
                $key = strtolower($content);

                if (isset(self::$matching[$key])) {
                    $tokens[$index]->setContent(self::$matching[$key]);
                }
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
        return 'Use short forms (bool) and (int) for casts and do not use !!.';
    }

}
