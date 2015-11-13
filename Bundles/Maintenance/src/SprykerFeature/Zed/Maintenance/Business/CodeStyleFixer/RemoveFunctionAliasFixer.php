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
 * Fixer RemoveFunctionAlias
 */
class RemoveFunctionAliasFixer extends AbstractFixer
{

    /**
     * @see http://php.net/manual/en/aliases.php
     *
     * @var array
     */
    public static $matching = [
        'is_integer' => 'is_int',
        'is_long' => 'is_int',
        'is_real' => 'is_float',
        'is_double' => 'is_float',
        'is_writeable' => 'is_writable',
        'join' => 'explode',
        'key_exists' => 'array_key_exists', // Deprecated function
        'sizeof' => 'count',
        'strchr' => 'strstr',
        'ini_alter' => 'ini_set',
        'fputs' => 'fwrite',
        'die' => 'exit',
        'chop' => 'rtrim',
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
     * @return void
     */
    protected function fixContent(Tokens $tokens) {
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

            $next = $tokens->getNextMeaningfulToken($index);
            if ($tokens[$next]->getContent() !== '(') {
                continue;
            }

            $tokens[$index]->setContent(self::$matching[$tokenContent]);
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
        return 'Always use one form of a function and remove its aliases.';
    }

}
