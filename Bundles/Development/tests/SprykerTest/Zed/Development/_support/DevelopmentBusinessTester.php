<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development;

use Codeception\Actor;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfiguration;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoader;
use Spryker\Zed\Development\Business\SnifferConfiguration\Builder\ArchitectureSnifferConfigurationBuilder;
use Spryker\Zed\Development\Business\SnifferConfiguration\Builder\SnifferConfigurationBuilderInterface;
use Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReader;
use Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Yaml\Parser;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class DevelopmentBusinessTester extends Actor
{
    use _generated\DevelopmentBusinessTesterActions;

    protected const DEFAULT_PRIORITY = 2;

    /**
     * @return \Spryker\Zed\Development\Business\SnifferConfiguration\Builder\SnifferConfigurationBuilderInterface
     */
    public function createArchitectureSnifferConfigurationBuilder(): SnifferConfigurationBuilderInterface
    {
        return new ArchitectureSnifferConfigurationBuilder(
            $this->createConfigurationReader(),
            static::DEFAULT_PRIORITY
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface
     */
    public function createConfigurationReader(): ConfigurationReaderInterface
    {
        return new ConfigurationReader(
            $this->createSymfonyYamlParser()
        );
    }

    /**
     * @return int
     */
    public function getDefaultPriority(): int
    {
        return static::DEFAULT_PRIORITY;
    }

    /**
     * @return \Symfony\Component\Yaml\Parser
     */
    protected function createSymfonyYamlParser(): Parser
    {
        return new Parser();
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoader
     */
    public function createCodeStyleSnifferConfigurationLoader(): CodeStyleSnifferConfigurationLoader
    {
        return new CodeStyleSnifferConfigurationLoader(
            $this->createConfigurationReader(),
            $this->createCodeStyleSnifferConfiguration()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfiguration
     */
    public function createCodeStyleSnifferConfiguration(): CodeStyleSnifferConfiguration
    {
        return new CodeStyleSnifferConfiguration(
            $this->createDevelopmentConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\DevelopmentConfig
     */
    public function createDevelopmentConfig(): DevelopmentConfig
    {
        return new DevelopmentConfig();
    }
}
