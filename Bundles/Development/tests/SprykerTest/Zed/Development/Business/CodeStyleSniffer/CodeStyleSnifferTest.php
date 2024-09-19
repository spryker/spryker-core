<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\CodeStyleSniffer;

use Codeception\Test\Unit;
use ReflectionMethod;
use ReflectionObject;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface;
use Spryker\Zed\Development\Business\Resolver\CodeStylePathResolver;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group CodeStyleSniffer
 * @group CodeStyleSnifferTest
 * Add your own group annotations below this line
 */
class CodeStyleSnifferTest extends Unit
{
    /**
     * @var string
     */
    protected const DEVELOPMENT_MODULE_PATH_REGEX = '#' . APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . '.+/Development/#';

    /**
     * @var \SprykerTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected $pathToCore = 'vendor/spryker/spryker/Bundles/';

    /**
     * @var string
     */
    protected $developmentModule = 'Spryker.Development';

    /**
     * The list of default CodeStyleSniffer options.
     *
     * @var array
     */
    protected $defaultOptions = [
        'module' => null,
        'sniffs' => null,
        'level' => 1,
        'explain' => false,
        'dry-run' => false,
        'fix' => false,
        'help' => false,
        'quiet' => false,
        'verbose' => false,
        'version' => false,
        'ansi' => false,
        'no-ansi' => false,
        'no-interaction' => false,
        'no-pre' => false,
        'no-post' => false,
        'path' => null,
    ];

    /**
     * @return void
     */
    public function testCheckCsForAllModulesRunsWithSpecificRuleset(): void
    {
        $resolvedPaths = $this->getResolvedPathsForAllSprykerModules();

        $developmentModulePath = current(preg_grep(static::DEVELOPMENT_MODULE_PATH_REGEX, array_keys($resolvedPaths)));
        $developmentLevel = $this->getDevelopmentCsLevel($resolvedPaths[$developmentModulePath]);

        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock(
            $this->normalizePathToModule($developmentModulePath),
            $developmentLevel,
        );

        $options = [
                'ignore' => 'vendor/',
                'module' => $this->developmentModule,
                'level' => $this->tester->getDefaultLevel(),
            ] + $this->defaultOptions;

        $codeStyleSnifferMock->checkCodeStyle($this->developmentModule, $options);
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInCoreModuleForLevelOne(): void
    {
        $options = [
            'ignore' => 'vendor/',
            'module' => $this->developmentModule,
            'level' => 1,
        ] + $this->defaultOptions;

        $developmentModulePath = dirname(__DIR__, 6) . DIRECTORY_SEPARATOR;
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($developmentModulePath, $options['level']);

        $codeStyleSnifferMock->checkCodeStyle($this->developmentModule, $options);
    }

    /**
     * @return void
     */
    public function testCheckCodeStyleRunsCommandInCoreModuleForLevelTwo(): void
    {
        $options = [
            'ignore' => 'vendor/',
            'module' => $this->developmentModule,
            'level' => 2,
        ] + $this->defaultOptions;

        $developmentModulePath = dirname(__DIR__, 6) . DIRECTORY_SEPARATOR;
        $codeStyleSnifferMock = $this->getCodeStyleSnifferMock($developmentModulePath, $options['level']);

        $codeStyleSnifferMock->checkCodeStyle($this->developmentModule, $options);
    }

    /**
     * @param string $developmentModulePath
     * @param int $level
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer
     */
    protected function getCodeStyleSnifferMock(string $developmentModulePath, int $level): CodeStyleSniffer
    {
        $developmentConfig = $this->tester->createDevelopmentConfig();
        $codingStandard = $developmentModulePath . 'ruleset.xml';

        if ($level === $this->tester->getDefaultPriority()) {
            /** @see \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfiguration::getCodingStandard() */
            $codingStandard = $developmentModulePath . 'rulesetStrict.xml';
        }

        $codeStyleSnifferMock = $this
            ->getMockBuilder(CodeStyleSniffer::class)
            ->setConstructorArgs([
                $developmentConfig,
                $this->tester->createCodeStylePathResolver(),
            ])
            ->setMethods(['runSnifferCommand'])
            ->getMock();

        $codeStyleSnifferMock
            ->method('runSnifferCommand')
            ->with(
                $this->buildPathWithProperRootDir($developmentModulePath),
                $this->callback(function ($subject) use ($codingStandard, $developmentModulePath) {
                    return is_callable([$subject, 'getCodingStandard']) &&
                        $subject->getCodingStandard($this->buildPathWithProperVendorDir($developmentModulePath)) === $codingStandard;
                }),
            );

        return $codeStyleSnifferMock;
    }

    /**
     * @return array<string, \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface>
     */
    protected function getResolvedPathsForAllSprykerModules(): array
    {
        $module = 'Spryker.all';
        $options = [
                'ignore' => 'vendor/',
                'module' => $module,
            ] + $this->defaultOptions;

        $reflectedResolvePathsMethod = new ReflectionMethod(CodeStylePathResolver::class, 'resolvePaths');
        $paths = $reflectedResolvePathsMethod->invokeArgs(
            $this->tester->createCodeStylePathResolver(),
            ['all', 'Spryker', null, $options],
        );

        return $paths;
    }

    /**
     * @param \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface $developmentCsConfiguration
     *
     * @return int
     */
    protected function getDevelopmentCsLevel(CodeStyleSnifferConfigurationInterface $developmentCsConfiguration): int
    {
        $reflectedObject = new ReflectionObject($developmentCsConfiguration);
        $reflectedModuleConfigProperty = $reflectedObject->getProperty('moduleConfig');
        $reflectedModuleConfigProperty->setAccessible(true);
        $moduleConfig = $reflectedModuleConfigProperty->getValue($developmentCsConfiguration);

        return $moduleConfig['level'] ?? $this->tester->getDefaultLevel();
    }

    /**
     * @param string $modulePath
     *
     * @return string
     */
    protected function buildPathWithProperRootDir(string $modulePath): string
    {
        return str_replace(APPLICATION_ROOT_DIR, APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR, $modulePath);
    }

    /**
     * @param string $modulePath
     *
     * @return string
     */
    protected function buildPathWithProperVendorDir(string $modulePath): string
    {
        return str_replace(APPLICATION_VENDOR_DIR, APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR, $modulePath);
    }

    /**
     * @param string $modulePath
     *
     * @return string
     */
    protected function normalizePathToModule(string $modulePath): string
    {
        return str_replace(APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR, APPLICATION_ROOT_DIR, $modulePath);
    }
}
