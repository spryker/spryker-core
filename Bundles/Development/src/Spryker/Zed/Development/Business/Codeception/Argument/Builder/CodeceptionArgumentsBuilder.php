<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Codeception\Argument\Builder;

use Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments;
use SprykerTest\Shared\Testify\Helper\SuiteFilterHelper;

class CodeceptionArgumentsBuilder implements CodeceptionArgumentsBuilderInterface
{
    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeTestConsole::OPTION_CONFIG_PATH
     */
    protected const OPTION_CONFIG_PATH = 'config';
    protected const OPTION_GROUP_INCLUDE = 'group';
    protected const OPTION_GROUP_EXCLUDE = 'exclude';
    protected const OPTION_VERBOSE = 'verbose';
    protected const OPTION_MODULE = 'module';

    /**
     * @var string[]
     */
    protected $defaultInclusiveTestGroups;

    /**
     * @param string[] $defaultInclusiveTestGroups
     */
    public function __construct(array $defaultInclusiveTestGroups)
    {
        $this->defaultInclusiveTestGroups = $defaultInclusiveTestGroups;
    }

    /**
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments
     */
    public function build(array $options): CodeceptionArguments
    {
        $codeceptionArguments = new CodeceptionArguments();

        $codeceptionArguments = $this->addConfigPath($codeceptionArguments, $options);
        $codeceptionArguments = $this->addInclusiveGroups($codeceptionArguments, $options);
        $codeceptionArguments = $this->addIncludeGroups($codeceptionArguments, $options);
        $codeceptionArguments = $this->addExcludeGroups($codeceptionArguments, $options);
        $codeceptionArguments = $this->addVerboseMode($codeceptionArguments, $options);

        return $codeceptionArguments;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments $codeceptionArguments
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments
     */
    protected function addConfigPath(CodeceptionArguments $codeceptionArguments, array $options): CodeceptionArguments
    {
        if (!empty($options[static::OPTION_CONFIG_PATH])) {
            return $codeceptionArguments->addArgument('-c', [$options[static::OPTION_CONFIG_PATH]]);
        }

        return $codeceptionArguments;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments $codeceptionArguments
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments
     */
    protected function addIncludeGroups(CodeceptionArguments $codeceptionArguments, array $options): CodeceptionArguments
    {
        if (!empty($options[static::OPTION_GROUP_INCLUDE])) {
            $codeceptionArguments->addArgument(
                '-g',
                explode(',', $options[static::OPTION_GROUP_INCLUDE])
            );
        }

        return $codeceptionArguments;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments $codeceptionArguments
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments
     */
    protected function addExcludeGroups(CodeceptionArguments $codeceptionArguments, array $options): CodeceptionArguments
    {
        if (!empty($options[static::OPTION_GROUP_EXCLUDE])) {
            $codeceptionArguments->addArgument(
                '-x',
                explode(',', $options[static::OPTION_GROUP_EXCLUDE])
            );
        }

        return $codeceptionArguments;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments $codeceptionArguments
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments
     */
    protected function addVerboseMode(CodeceptionArguments $codeceptionArguments, array $options): CodeceptionArguments
    {
        if (!empty($options[static::OPTION_VERBOSE])) {
            return $codeceptionArguments->addArgument('-v');
        }

        return $codeceptionArguments;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments $codeceptionArguments
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments
     */
    protected function addInclusiveGroups(CodeceptionArguments $codeceptionArguments, array $options): CodeceptionArguments
    {
        if (!empty($options[static::OPTION_MODULE])) {
            $codeceptionArguments = $this->enableSuiteFilterExtension($codeceptionArguments);
            $codeceptionArguments = $this->buildInlineExtensionConfig($codeceptionArguments, $options);
        }

        return $codeceptionArguments;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments $codeceptionArguments
     *
     * @return \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments
     */
    protected function enableSuiteFilterExtension(CodeceptionArguments $codeceptionArguments): CodeceptionArguments
    {
        return $codeceptionArguments->addArgument(
            '--ext',
            ['\\' . SuiteFilterHelper::class]
        );
    }

    /**
     * @param \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments $codeceptionArguments
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\Codeception\Argument\CodeceptionArguments
     */
    protected function buildInlineExtensionConfig(CodeceptionArguments $codeceptionArguments, array $options): CodeceptionArguments
    {
        $extensionInlineConfigTemplate = 'extensions: config: %s: inclusive: [%s]';

        $inclusiveGroups = $this->defaultInclusiveTestGroups;
        $inclusiveGroups[] = $options[static::OPTION_MODULE];

        $suiteFilterHelperClassName = '\\' . SuiteFilterHelper::class;
        $inclusiveGroupsAsString = implode(',', $inclusiveGroups);

        $extensionInlineConfig = sprintf(
            $extensionInlineConfigTemplate,
            $suiteFilterHelperClassName,
            $inclusiveGroupsAsString
        );

        return $codeceptionArguments->addArgument(
            '-o',
            [$extensionInlineConfig]
        );
    }
}
