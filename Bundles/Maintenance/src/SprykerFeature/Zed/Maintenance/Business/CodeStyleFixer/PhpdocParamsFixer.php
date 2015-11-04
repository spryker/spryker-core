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
 * Fixer PhpdocParams
 *
 * Copy of the php-cs-fixer file with very little adjustments
 *
 * @author Mark Scherer
 */
class PhpdocParamsFixer extends AbstractFixer
{

    /**
     * @var string
     */
    protected $regex;

    /**
     * @var string
     */
    protected $regexCommentLine;

    public function __construct()
    {
        // e.g. @param <hint> <$var>
        $paramTag = '(?P<tag>param)\s+(?P<hint>[^$]+?)\s+(?P<var>&?\$[^\s]+)';
        // e.g. @return <hint>
        $otherTags = '(?P<tag2>return|throws|var|type)\s+(?P<hint2>[^\s]+?)';
        // optional <desc>
        $desc = '(?:\s+(?P<desc>.*)|\s*)';

        $this->regex = '/^(?P<indent>(?: {4})*) \* @(?:' . $paramTag . '|' . $otherTags . ')' . $desc . '$/';
        $this->regexCommentLine = '/^(?P<indent>(?: {4})*) \*(?! @)(?:\s+(?P<desc>.+))(?<!\*\/)$/';
    }

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
        return 'No alignment for @param, @throws, @return, @var, and @type phpdoc tags.';
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
        $lines = Utils::splitLines($content);

        $l = count($lines);

        for ($i = 0; $i < $l; ++$i) {
            $items = [];
            $matches = $this->getMatches($lines[$i]);

            if ($matches === null) {
                continue;
            }

            $current = $i;
            $items[] = $matches;

            while ($matches = $this->getMatches($lines[++$i], true)) {
                $items[] = $matches;
            }

            // compute the max length of the tag, hint and variables
            $tagMax = 0;
            $hintMax = 0;
            $varMax = 0;

            foreach ($items as $item) {
                if ($item['tag'] === null) {
                    continue;
                }

                $tagMax = max($tagMax, strlen($item['tag']));
                $hintMax = max($hintMax, strlen($item['hint']));
                $varMax = max($varMax, strlen($item['var']));
            }

            $currTag = null;

            // update
            foreach ($items as $j => $item) {
                if ($item['tag'] === null) {
                    if ($item['desc'][0] === '@') {
                        $lines[$current + $j] = $item['indent'] . ' * ' . $item['desc'] . "\n";
                        continue;
                    }

                    $line =
                        $item['indent']
                        . ' *  '
                        . ' '
                        . $item['desc']
                        . "\n";

                    $lines[$current + $j] = $line;

                    continue;
                }

                $currTag = $item['tag'];

                $line =
                    $item['indent']
                    . ' * @'
                    . $item['tag']
                    . ' '
                    . $item['hint']
                ;

                if (!empty($item['var'])) {
                    $line .=
                        ' '
                        . $item['var']
                        . (
                        !empty($item['desc'])
                            ? ' ' . $item['desc'] . "\n"
                            : "\n"
                        )
                    ;
                } elseif (!empty($item['desc'])) {
                    $line .= ' ' . $item['desc'] . "\n";
                } else {
                    $line .= "\n";
                }

                $lines[$current + $j] = $line;
            }
        }

        return implode($lines);
    }

    /**
     * Get all matches.
     *
     * @param string $line
     * @param bool $matchCommentOnly
     *
     * @return string[]|null
     */
    protected function getMatches($line, $matchCommentOnly = false)
    {
        if (preg_match($this->regex, $line, $matches)) {
            if (!empty($matches['tag2'])) {
                $matches['tag'] = $matches['tag2'];
                $matches['hint'] = $matches['hint2'];
            }

            return $matches;
        }

        if ($matchCommentOnly && preg_match($this->regexCommentLine, $line, $matches)) {
            $matches['tag'] = null;
            $matches['var'] = '';
            $matches['hint'] = '';

            return $matches;
        }
    }

}
