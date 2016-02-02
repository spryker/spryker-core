<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\CodeStyleFixer;

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
     * @param \Symfony\CS\Tokenizer\Tokens|Token[] $tokens
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
                $this->addNewDocBlock($tokens, $docBlockIndex, $returnType);
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
     * @param \Symfony\CS\DocBlock\DocBlock $doc
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
     * @param \Symfony\CS\Tokenizer\Tokens|Token[] $tokens
     * @param int $index
     *
     * @return string|null
     */
    protected function detectReturnType(Tokens $tokens, $index)
    {
        $type = 'void';

        $methodStartIndex = $tokens->getNextMeaningfulToken($index);
        $methodStartIndex = $tokens->getNextTokenOfKind($methodStartIndex, ['{']);
        if ($methodStartIndex === null) {
            return null;
        }

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
     * @param \Symfony\CS\Tokenizer\Tokens|Token[] $tokens
     * @param int $index
     * @param int|null $braceCounter
     *
     * @return int|null
     */
    protected function detectMethodEnd(Tokens $tokens, $index, $braceCounter = null)
    {
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
     * If no docblock can be found we use the beginning of the line.
     *
     * @param \Symfony\CS\Tokenizer\Tokens|Token[] $tokens
     * @param int $index
     *
     * @return int
     */
    protected function getDocBlockToFunction(Tokens $tokens, $index)
    {
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

    /**
     * @param \Symfony\CS\Tokenizer\Tokens|Token[] $tokens
     * @param int $docBlockIndex
     * @param string $returnType
     *
     * @return void
     */
    protected function addNewDocBlock($tokens, $docBlockIndex, $returnType)
    {
        $docBlockTemplate = <<<TXT
/**
     * @return $returnType
     */

TXT;
        $docBlockTemplate = $docBlockTemplate . '    ' . $tokens[$docBlockIndex]->getContent();

        $tokens[$docBlockIndex]->setContent($docBlockTemplate);
    }

}
