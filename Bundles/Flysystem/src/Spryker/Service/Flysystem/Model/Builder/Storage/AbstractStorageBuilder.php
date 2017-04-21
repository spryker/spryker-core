<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder\Storage;

use Generated\Shared\Transfer\FlysystemStorageConfigTransfer;
use Spryker\Service\Flysystem\Model\Builder\FlysystemStorageBuilderInterface;

abstract class AbstractStorageBuilder implements FlysystemStorageBuilderInterface
{

    /**
     * @var \Generated\Shared\Transfer\FlysystemStorageConfigTransfer
     */
    protected $config;

    /**
     * @var \Spryker\Service\Flysystem\Model\Builder\FlysystemStorageBuilderInterface
     */
    protected $builder;

    /**
     * @throws \Spryker\Service\Flysystem\Exception\FlysystemInvalidConfigurationException
     *
     * @return void
     */
    abstract protected function validateConfig();

    /**
     * @return \Spryker\Service\Flysystem\Model\Builder\FlysystemStorageBuilderInterface
     */
    abstract protected function createFlysystemBuilder();

    /**
     * @param \Generated\Shared\Transfer\FlysystemStorageConfigTransfer $configTransfer
     */
    public function __construct(FlysystemStorageConfigTransfer $configTransfer)
    {
        $this->config = $configTransfer;
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\FlysystemStorageInterface
     */
    public function build()
    {
        $this->validateMandatoryConfigFields();
        $this->validateConfig();

        $fileSystemBuilder = $this->createFlysystemBuilder();

        return $fileSystemBuilder->build();
    }

    /**
     * @return void
     */
    protected function validateMandatoryConfigFields()
    {
        $this->config->requireName();
        $this->config->requireType();
        $this->config->requireData();
    }

}
