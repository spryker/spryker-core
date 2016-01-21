<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business;

use Spryker\Zed\Development\Business\CodeBuilder\Bridge\BridgeBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\BundleFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\EngineBundleFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorClient;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorFacade;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorQueryContainer;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\UseStatement;
use Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\GraphBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\GraphBuilder2;
use Spryker\Zed\Development\Business\DependencyTree\DependencyReport\DependencyReport;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\JsonDependencyTreeReader;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter\JsonDependencyTreeWriter;
use Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor;
use Spryker\Zed\Development\Business\DependencyTree\Finder;
use Spryker\Zed\Development\Business\PhpMd\PhpMdRunner;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Development\Business\CodeStyleFixer\CodeStyleFixer;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;
use Spryker\Zed\Development\Business\CodeTest\CodeTester;
use Spryker\Zed\Development\DevelopmentConfig;

/**
 * @method DevelopmentConfig getConfig()
 */
class DevelopmentBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CodeStyleFixer
     */
    public function createCodeStyleFixer()
    {
        return new CodeStyleFixer(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return CodeStyleSniffer
     */
    public function createCodeStyleSniffer()
    {
        return new CodeStyleSniffer(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return CodeTester
     */
    public function createCodeTester()
    {
        return new CodeTester(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return PhpMdRunner
     */
    public function createPhpMdRunner()
    {
        return new PhpMdRunner(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return BridgeBuilder
     */
    public function createBridgeBuilder()
    {
        return new BridgeBuilder();
    }

}
