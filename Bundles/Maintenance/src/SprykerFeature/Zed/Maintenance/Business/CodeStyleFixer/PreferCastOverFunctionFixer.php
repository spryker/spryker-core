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
 * Fixer PreferCastOverFunction
 */
class PreferCastOverFunctionFixer extends AbstractFixer
{

    /**
     * @var array
     */
    protected static $matching = [
        'strval' => 'string',
        'intval' => 'int',
        'floatval' => 'float',
        'boolval' => 'bool',
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
        $wrongTokens = [T_FUNCTION, T_OBJECT_OPERATOR, T_NEW];

        foreach ($tokens as $index => $token) {
            $tokenContent = strtolower($token->getContent());
            if (empty($tokenContent) || !isset(self::$matching[$tokenContent])) {
                continue;
            }

            $prevIndex = $tokens->getPrevNonWhitespace($index);
            if (in_array($tokens[$prevIndex]->getId(), $wrongTokens, true)) {
                continue;
            }

            $openingBrace = $tokens->getNextMeaningfulToken($index);
            if ($tokens[$openingBrace]->getContent() !== '(') {
                continue;
            }

            $closingBrace = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $openingBrace);

            // Skip for non-trivial cases
            for ($i = $openingBrace + 1; $i < $closingBrace; ++$i) {
                if ($tokens[$i]->equals(',')) {
                    continue 2;
                }
            }

            $cast = '(' . self::$matching[$tokenContent] . ')';
            $tokens[$index]->setContent($cast);
            $tokens[$openingBrace]->setContent('');
            $tokens[$closingBrace]->setContent('');
        }
    }

    /**
     * Must run before any cast modifications
     *
     * @return int
     */
    public function getPriority()
    {
        return 10;
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
        return 'Always use simple casts instead of method invocation.';
    }

}
