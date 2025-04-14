<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Gui\Communication\Plugin\Twig;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Zed\Gui\Communication\Plugin\Twig\AssetsPathTwigPlugin;
use SprykerTest\Zed\Gui\GuiCommunicationTester;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Gui
 * @group Communication
 * @group Plugin
 * @group Twig
 * @group AssetsPathTwigPluginTest
 * Add your own group annotations below this line
 */
class AssetsPathTwigPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Gui\GuiCommunicationTester
     */
    protected GuiCommunicationTester $tester;

    /**
     * @var string
     */
    protected const TEST_ASSETS_PATH = '/assets/';

    /**
     * @var string
     */
    protected const TEST_BUILD_HASH = 'abc123';

    /**
     * @return void
     */
    public function testExtendShouldAddAssetsPathWithBuildHash(): void
    {
        // Arrange
        $twigMock = $this->createMock(Environment::class);
        $containerMock = $this->createMock(ContainerInterface::class);

        $plugin = new AssetsPathTwigPlugin();

        putenv('SPRYKER_BUILD_HASH=' . static::TEST_BUILD_HASH);

        $this->tester->mockConfigMethod('getZedAssetsPath', static::TEST_ASSETS_PATH);

        // Expect
        $twigMock->expects($this->once())
            ->method('addFunction')
            ->with($this->callback(function (TwigFunction $function) {
                $result = call_user_func($function->getCallable(), 'css/style.css');
                $this->assertSame(
                    static::TEST_ASSETS_PATH . 'css/style.css?v=' . static::TEST_BUILD_HASH,
                    $result,
                );

                return true;
            }));

        // Act
        $plugin->extend($twigMock, $containerMock);
    }
}
