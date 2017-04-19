<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Service\Storage;

use Spryker\Zed\FileSystem\Service\Exception\FileSystemInvalidConfigurationException;

abstract class AbstractBuilder implements BuilderInterface
{

    const NAME = 'name';
    const TYPE = 'type';
    const ROOT = 'root';
    const TITLE = 'title';
    const ICON = 'icon';

    /**
     * @var array
     */
    protected $mandatoryConfigFields = [
        self::NAME,
        self::TYPE,
        self::ROOT,
        self::TITLE,
    ];

    /**
     * @var array
     */
    protected $optionalConfigFields = [
        self::ICON,
    ];

    /**
     * Builder specific mandatory config
     *
     * @var array
     */
    protected $builderMandatoryConfigFields = [];

    /**
     * Builder specific optional config
     *
     * @var array
     */
    protected $builderOptionalConfigFields = [];

    /**
     * @var array
     */
    protected $config;

    /**
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface
     */
    abstract protected function buildStorage();

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @throws \Spryker\Zed\FileSystem\Service\Exception\FileSystemInvalidConfigurationException
     *
     * @return void
     */
    protected function validateConfig()
    {
        $fieldsToCheck = array_merge($this->mandatoryConfigFields, $this->builderMandatoryConfigFields);
        $missing = array_diff_key(array_flip($fieldsToCheck), $this->config);

        if (count($missing)) {
            throw new FileSystemInvalidConfigurationException(
                sprintf(
                    'Missing required config values for: "%s" in FileSystemStorage:%s "%s"',
                    implode(', ', array_keys($missing)),
                    $this->config[self::TYPE],
                    $this->config[self::NAME]
                )
            );
        }
    }

    /**
     * @return void
     */
    protected function mergeConfigWithOptionalKeys($configToMerge)
    {
        $configDataWithOptionalKeys = array_merge(
            $this->config,
            array_combine(
                array_values($configToMerge),
                array_fill(0, count($configToMerge), '')
            )
        );

        $this->config = array_merge($configDataWithOptionalKeys, $this->config);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigWithOptionalKeys($this->optionalConfigFields);
        $this->mergeConfigWithOptionalKeys($this->builderOptionalConfigFields);
    }

    /**
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface
     */
    public function build()
    {
        $this->configure();
        $this->validateConfig();

        return $this->buildStorage();
    }

}
