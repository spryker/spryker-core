<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business\CodeStyleFixer;

use Symfony\CS\AbstractFixer;
use Symfony\CS\DocBlock\DocBlock;
use Symfony\CS\FixerInterface;
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

class PhpdocReturnVoidFixer extends AbstractFixer
{

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Add a return void line to docblock if possible.';
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return FixerInterface::NONE_LEVEL;
    }

    /**
     * {@inheritdoc}
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
        foreach ($tokens->findGivenKind(T_FUNCTION) as $index => $token) {
            // Removal of return line for constructor and destructor should be another fixer
            $nextIndex = $tokens->getNextMeaningfulToken($index);
            if ($tokens[$nextIndex]->getContent() === '__construct' || $tokens[$nextIndex]->getContent() === '__destruct') {
                continue;
            }

            // Don't mess with closures
            $prevIndex = $tokens->getPrevMeaningfulToken($index);
            if (!$tokens[$prevIndex]->isGivenKind([T_PUBLIC, T_PROTECTED, T_PRIVATE, T_FINAL, T_STATIC])) {
                continue;
            }

            $returnType = $this->detectReturnType($tokens, $index);
            if ($returnType === null) {
                continue;
            }

            $docBlockIndex = $this->getDocBlockToFunction($tokens, $index);
            if (!$tokens[$docBlockIndex]->isGivenKind([T_DOC_COMMENT])) {
                // Try to not fix for now, as it seems to create indentation issues with the following method
                //continue;

                $docBlockTemplate = <<<TXT
/**
     * @return $returnType
     */

TXT;
                $docBlockTemplate = $docBlockTemplate . '    ' . $tokens[$docBlockIndex]->getContent();

                $tokens[$docBlockIndex]->setContent($docBlockTemplate);
                continue;
            }

            $docBlock = new DocBlock($tokens[$docBlockIndex]->getContent());

            $annotations = $docBlock->getAnnotationsOfType('return');
            if (!empty($annotations)) {
                continue;
            }

            $this->addReturnAnnotation($docBlock, $returnType);

            $tokens[$docBlockIndex]->setContent($docBlock->getContent());
        }
    }

    /**
     * @param DocBlock $doc
     *
     * @return void
     */
    protected function addReturnAnnotation(DocBlock $doc, $returnType = 'void')
    {
        $lines = $doc->getLines();
        $count = count($lines);

        $lastLine = $doc->getLine($count - 1);
        $lastLineContent = $lastLine->getContent();
        $whiteSpaceLength = strlen($lastLineContent) - 2;

        $returnLine = str_repeat(' ', $whiteSpaceLength) . '* @return ' . $returnType;
        $lastLineContent = $returnLine . "\n" . $lastLineContent;

        $lastLine->setContent($lastLineContent);
    }

    /**
     * For right now we only try to fix void
     *
     * @param Tokens|Token[] $tokens
     * @param int $index
     *
     * @return string|null
     */
    protected function detectReturnType(Tokens $tokens, $index)
    {
        $type = 'void';

        $methodStartIndex = $tokens->getNextMeaningfulToken($index);
        $methodStartIndex = $tokens->getNextTokenOfKind($methodStartIndex, ['{']);

        $methodEndIndex = $this->detectMethodEnd($tokens, $methodStartIndex, 1);

        for ($i = $methodStartIndex + 1; $i < $methodEndIndex; ++$i) {
            if ($tokens[$i]->isGivenKind([T_FUNCTION])) {
                $endIndex = $this->detectMethodEnd($tokens, $i);
                $i = $endIndex;
                continue;
            }

            if (!$tokens[$i]->isGivenKind([T_RETURN])) {
                continue;
            }

            $nextIndex = $tokens->getNextMeaningfulToken($i);
            if (!$tokens[$nextIndex]->equals(';')) {
                return null;
            }
        }

        return $type;
    }

    /**
     * @param Tokens|Token[] $tokens
     * @param int $index
     * @param int|null $braceCounter
     *
     * @return int
     */
    protected function detectMethodEnd(Tokens $tokens, $index, $braceCounter = null)
    {
        // Does not work :(
        //$methodEndIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $methodStartIndex);
        $nextIndex = $index;

        while (true) {
            $nextIndex = $tokens->getNextMeaningfulToken($nextIndex);

            if ($nextIndex === null) {
                break;
            }

            if ($tokens[$nextIndex]->equals('{')) {
                ++$braceCounter;
                continue;
            }

            if ($tokens[$nextIndex]->equals('}')) {
                --$braceCounter;
            }

            if ($braceCounter === 0) {
                return $nextIndex;
            }
        }

        return null;
    }

    /**
     * @param Tokens|Token[] $tokens
     * @param int $index
     *
     * @return int
     */
    protected function getDocBlockToFunction(Tokens $tokens, $index)
    {
        //Does not work this way
        //$docBlockIndex = $tokens->getPrevTokenOfKind($index, [T_DOC_COMMENT]);

        // Find beginning of line
        $i = $index;
        while ($tokens[$i]->getLine() === $tokens[$index]->getLine()) {
            --$i;
        }
        ++$i;
        $endIndex = $i;
        $startIndex = $tokens->getPrevMeaningfulToken($i);

        $docBlockIndex = $endIndex;
        for ($i = $endIndex - 1; $i > $startIndex; --$i) {
            if ($tokens[$i]->isGivenKind([T_DOC_COMMENT])) {
                $docBlockIndex = $i;
                break;
            }
        }

        return $docBlockIndex;
    }

}
