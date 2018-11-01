<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFinder;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Runner;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\SeparatorToCamelCase;

$manualAutoload = APPLICATION_VENDOR_DIR . '/squizlabs/php_codesniffer/autoload.php';
if (!class_exists(Config::class) && file_exists($manualAutoload)) {
    require $manualAutoload;
}

class ExternalDependency extends AbstractDependencyFinder
{
    /**
     * @var array
     */
    protected $externalToInternalNamespaceMap;

    /**
     * @param array $externalToInternalNamespaceMap
     */
    public function __construct(array $externalToInternalNamespaceMap)
    {
        $this->externalToInternalNamespaceMap = $externalToInternalNamespaceMap;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return void
     */
    public function addDependencies(SplFileInfo $fileInfo)
    {
        $_SERVER['argv'] = [];
        if (!defined('STDIN')) {
            define('STDIN', fopen(__FILE__, 'r'));
        }

        $file = $this->getFile($fileInfo);
        $tokens = $file->getTokens();
        $pointer = 0;

        $classNames = [];
        while ($foundPosition = $file->findNext([T_NEW, T_USE, T_DOUBLE_COLON], $pointer)) {
            $pointer = $foundPosition + 1;
            $currentToken = $tokens[$foundPosition];

            if ($currentToken['type'] === 'T_NEW' || $currentToken['type'] === 'T_USE') {
                $pointer = $foundPosition + 2;
                $endOfNew = $file->findNext([T_SEMICOLON, T_OPEN_PARENTHESIS, T_WHITESPACE, T_DOUBLE_COLON], $pointer);
                $classNameParts = array_slice($tokens, $pointer, $endOfNew - $foundPosition - 2);
                $classNames[] = $this->buildClassName($classNameParts);
            }

            if ($currentToken['type'] === 'T_DOUBLE_COLON') {
                $pointer = $foundPosition + 1;
                $startOf = $file->findPrevious([T_OPEN_PARENTHESIS, T_WHITESPACE, T_OPEN_SQUARE_BRACKET], $foundPosition - 1) + 1;
                $classNameParts = array_slice($tokens, $startOf, $foundPosition - $startOf);
                $classNames[] = $this->buildClassName($classNameParts);
            }
        }

        $classNames = array_unique($classNames);

        foreach ($classNames as $className) {
            $className = ltrim($className, '\\');

            if (strpos($className, '_') === false && strpos($className, '\\') === false) {
                continue;
            }

            if (strpos($className, 'Spryker') !== false
                || strpos($className, 'Generated') !== false
                || strpos($className, 'Orm') !== false
                || strpos($className, 'static') !== false
                || strpos($className, 'self') !== false
            ) {
                continue;
            }

            $dependencyInformation[DependencyTree::META_FOREIGN_LAYER] = 'external';
            $dependencyInformation[DependencyTree::META_FOREIGN_CLASS_NAME] = $className;
            $dependencyInformation[DependencyTree::META_FOREIGN_IS_EXTERNAL] = true;

            $to = $this->getInternalBundleNameForExternalDependency($className);

            $this->addDependency($fileInfo, $to, $dependencyInformation);
        }

        $this->cleanAutoloader();
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return \PHP_CodeSniffer\Files\File
     */
    protected function getFile(SplFileInfo $fileInfo)
    {
        $phpcs = new Runner();

        if (!defined('PHP_CODESNIFFER_CBF')) {
            define('PHP_CODESNIFFER_CBF', false);
        }
        // Explicitly specifying standard prevents it from searching for phpcs.xml type files.
        $config = new Config(['--standard=PSR2']);
        $phpcs->config = $config;
        $phpcs->init();
        $ruleset = new Ruleset($config);
        $fileObject = new File($fileInfo->getPathname(), $ruleset, $config);
        $fileObject->setContent($fileInfo->getContents());
        $fileObject->parse();

        return $fileObject;
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function getInternalBundleNameForExternalDependency($className)
    {
        foreach ($this->externalToInternalNamespaceMap as $namespace => $internalComposerBundleName) {
            if (strpos($className, $namespace) !== false) {
                $foreignBundle = substr($internalComposerBundleName, 8);
                $filter = new SeparatorToCamelCase('-');

                return ucfirst($filter->filter($foreignBundle));
            }
        }

        return 'external';
    }

    /**
     * @param array $classNameParts
     *
     * @return string
     */
    private function buildClassName(array $classNameParts)
    {
        $className = '';
        foreach ($classNameParts as $classNamePart) {
            $className .= $classNamePart['content'];
        }

        return $className;
    }

    /**
     * @return void
     */
    private function cleanAutoloader()
    {
        $autoloadFunctions = spl_autoload_functions();
        $codeSnifferAutoloadFunction = false;

        foreach ($autoloadFunctions as $autoloadFunction) {
            if (is_array($autoloadFunction) && $autoloadFunction[0] === 'PHP_CodeSniffer') {
                $codeSnifferAutoloadFunction = $autoloadFunction;
            }
        }

        if ($codeSnifferAutoloadFunction) {
            spl_autoload_unregister($codeSnifferAutoloadFunction);
        }
    }
}
