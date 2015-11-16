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
 * Fixer PhpdocPipe
 */
class PhpdocPipeFixer extends AbstractFixer
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
        return 'No space around pipe (|) in doc block lines.';
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

            if (!$matches) {
                continue;
            }

            $current = $i;
            $items[] = $matches;

            while ($matches = $this->getMatches($lines[++$i], true)) {
                $items[] = $matches;
            }

            foreach ($items as $j => $item) {
                $pieces = explode('|', $item['hint']);

                $hints = [];
                foreach ($pieces as $piece) {
                    $hints[] = trim($piece);
                }

                $desc = trim($item['desc']);

                while (!empty($desc) && mb_substr($desc, 0, 1) === '|') {
                    $desc = trim(mb_substr($desc, 1));

                    $pos = mb_strpos($desc, ' ');
                    if ($pos > 0) {
                        $hints[] = trim(mb_substr($desc, 0, $pos));
                        $desc = trim(mb_substr($desc, $pos));
                    } else {
                        $hints[] = $desc;
                        $desc = '';
                    }
                }

                $item['hint'] = implode('|', $hints);
                $item['desc'] = $desc;

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
     * @return string[]
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

        return [];
    }

}
