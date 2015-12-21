<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Sniffs\DependencyProvider;

use Spryker\Sniffs\AbstractSniffs\AbstractSprykerSniff;

class FacadeNotInBridgeReturnedSniff extends AbstractSprykerSniff
{

    /**
     * @return array
     */
    public function register()
    {
        return [
            T_CLOSURE,
        ];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        if (!$this->isProvider($phpCsFile)) {
            return;
        }

        if ($this->isFacadeNotInBridgeReturned($phpCsFile, $stackPointer)) {
            $phpCsFile->addError(
                $this->getClassName($phpCsFile) . ' returns a facade directly. Fix this by add a bridge and inject the given facade.',
                $stackPointer
            );
        }
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     *
     * @return bool
     */
    protected function isProvider(\PHP_CodeSniffer_File $phpCsFile)
    {
        $className = $this->getClassName($phpCsFile);
        $bundleName = $this->getBundle($phpCsFile);

        $providerName = $bundleName . 'DependencyProvider';
        $stringLength = strlen($providerName);
        $relevantClassNamePart = substr($className, -$stringLength);

        return ($relevantClassNamePart === $providerName);
    }

    /**
     * @param \PHP_CodeSniffer_File $phpCsFile
     * @param int $stackPointer
     *
     * @return bool
     */
    private function isFacadeNotInBridgeReturned(\PHP_CodeSniffer_File $phpCsFile, $stackPointer)
    {
        $tokens = $phpCsFile->getTokens();
        $returnPointer = $phpCsFile->findNext(T_RETURN, $stackPointer);
        $endOfLinePointer = $phpCsFile->findEndOfStatement($returnPointer);

        $statementTokens = array_slice($tokens, $returnPointer, $endOfLinePointer - $returnPointer);
        $statement = $this->parseTokensContent($statementTokens);

        if (preg_match('/return \$container->getLocator\(\)->(.*?)\(\)->facade\(\)/', $statement)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $tokens
     *
     * @return string
     */
    private function parseTokensContent(array $tokens)
    {
        $statement = '';
        foreach ($tokens as $token) {
            $statement .= $token['content'];
        }

        return $statement;
    }

}
