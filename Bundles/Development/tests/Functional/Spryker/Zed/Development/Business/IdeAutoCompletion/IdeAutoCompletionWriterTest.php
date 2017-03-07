<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Development\Business\IdeAutoCompletion;

use Codeception\TestCase\Test;
use Development\FunctionalTester;
use Development\Module\IdeAutoCompletion;
use Spryker\Zed\Development\Business\DevelopmentBusinessFactory;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Spryker\Zed\Development\DevelopmentConfig;
use Spryker\Zed\Development\DevelopmentDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Development
 * @group Business
 * @group IdeAutoCompletion
 * @group IdeAutoCompletionWriterTest
 */
class IdeAutoCompletionWriterTest extends Test
{

    /**
     * @return void
     */
    public function testWriterCreatesYvesAutoCompletionFiles()
    {
        $i = $this->createFunctionalTester();

        $i->execute(function () {
            $this
                ->getDevelopmentBusinessFactory()
                ->createYvesIdeAutoCompletionWriter()
                ->writeCompletionFiles();
        });

        $i->canSeeFileFound('AutoCompletion.php', IdeAutoCompletion::TEST_TARGET_DIRECTORY . 'Generated/Yves/Ide/');
        $i->canSeeFileFound('BundleAutoCompletion.php', IdeAutoCompletion::TEST_TARGET_DIRECTORY . 'Generated/Yves/Ide/');
    }

    /**
     * @return void
     */
    public function testWriterCreatesZedAutoCompletionFiles()
    {
        $i = $this->createFunctionalTester();

        $i->execute(function () {
            $this
                ->getDevelopmentBusinessFactory()
                ->createZedIdeAutoCompletionWriter()
                ->writeCompletionFiles();
        });

        $i->canSeeFileFound('AutoCompletion.php', IdeAutoCompletion::TEST_TARGET_DIRECTORY . 'Generated/Zed/Ide/');
        $i->canSeeFileFound('BundleAutoCompletion.php', IdeAutoCompletion::TEST_TARGET_DIRECTORY . 'Generated/Zed/Ide/');
    }

    /**
     * @return void
     */
    public function testWriterCreatesClientAutoCompletionFiles()
    {
        $i = $this->createFunctionalTester();

        $i->execute(function () {
            $this
                ->getDevelopmentBusinessFactory()
                ->createClientIdeAutoCompletionWriter()
                ->writeCompletionFiles();
        });

        $i->canSeeFileFound('AutoCompletion.php', IdeAutoCompletion::TEST_TARGET_DIRECTORY . 'Generated/Client/Ide/');
        $i->canSeeFileFound('BundleAutoCompletion.php', IdeAutoCompletion::TEST_TARGET_DIRECTORY . 'Generated/Client/Ide/');
    }

    /**
     * @return void
     */
    public function testWriterCreatesServiceAutoCompletionFiles()
    {
        $i = $this->createFunctionalTester();

        $i->execute(function () {
            $this
                ->getDevelopmentBusinessFactory()
                ->createServiceIdeAutoCompletionWriter()
                ->writeCompletionFiles();
        });

        $i->canSeeFileFound('AutoCompletion.php', IdeAutoCompletion::TEST_TARGET_DIRECTORY . 'Generated/Service/Ide/');
        $i->canSeeFileFound('BundleAutoCompletion.php', IdeAutoCompletion::TEST_TARGET_DIRECTORY . 'Generated/Service/Ide/');
    }

    /**
     * @return \Development\FunctionalTester
     */
    protected function createFunctionalTester()
    {
        return new FunctionalTester($this->getScenario());
    }

    /**
     * @return \Spryker\Zed\Development\Business\DevelopmentBusinessFactory
     */
    protected function getDevelopmentBusinessFactory()
    {
        $container = new Container();
        $dependencyProvider = new DevelopmentDependencyProvider();
        $container = $dependencyProvider->provideBusinessLayerDependencies($container);

        $factory = new DevelopmentBusinessFactory();
        $factory->setContainer($container);

        $factory->setConfig($this->getDevelopmentConfigMock());

        return $factory;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Development\DevelopmentConfig
     */
    protected function getDevelopmentConfigMock()
    {
        $configMock = $this
            ->getMockBuilder(DevelopmentConfig::class)
            ->setMethods(['getDefaultIdeAutoCompletionOptions'])
            ->getMock();

        $configMock
            ->method('getDefaultIdeAutoCompletionOptions')
            ->willReturn([
                IdeAutoCompletionOptionConstants::TARGET_BASE_DIRECTORY => IdeAutoCompletion::TEST_TARGET_DIRECTORY,
                IdeAutoCompletionOptionConstants::TARGET_DIRECTORY_PATTERN => sprintf(
                    'Generated/%s/Ide',
                    IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER
                ),
                IdeAutoCompletionOptionConstants::TARGET_NAMESPACE_PATTERN => sprintf(
                    'Generated\%s\Ide',
                    IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER
                ),
            ]);

        return $configMock;
    }

}
