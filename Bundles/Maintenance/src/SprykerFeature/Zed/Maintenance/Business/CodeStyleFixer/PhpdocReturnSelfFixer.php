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

class PhpdocReturnSelfFixer extends AbstractFixer
{

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        /** @var Token $token */
        foreach ($tokens->findGivenKind(T_DOC_COMMENT) as $token) {
            $token->setContent($this->fixDocBlock($token->getContent()));
        }

        return $tokens->generateCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Methods returning self should be `@return self`';
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        /*
         * Should be run after all other docblock fixers.
         */
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
     * Fix a given docblock.
     *
     * @param string $content
     *
     * @return string
     */
    protected function fixDocBlock($content)
    {
        $replace = function ($matches) {
            dump($matches);
            return '@return ' . $matches[1] . 'self' . $matches[2];
        };

        $content = preg_replace_callback('/\@return\s+([\w+\|]+\||\s*)\$this(\|[\w+\|]+|\s*)\b/', $replace, $content);

        return $content;
    }

}
