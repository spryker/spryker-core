<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage;

use Spryker\Service\FileSystem\Model\Exception\FileSystemInvalidFilenameException;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

abstract class AbstractBuilder implements BuilderInterface
{

    /**
     * @var \Generated\Shared\Transfer\FileSystemStorageConfigTransfer
     */
    protected $configTransfer;

    /**
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface
     */
    abstract protected function buildStorage();

    /**
     * @throws \Spryker\Service\FileSystem\Model\Exception\FileSystemInvalidConfigurationException
     *
     * @return void
     */
    abstract protected function validateConfig();

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $configTransfer
     */
    public function __construct(AbstractTransfer $configTransfer)
    {
        $this->configTransfer = $configTransfer;
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface
     */
    public function build()
    {
        $this->validateMandatoryConfigFields();
        $this->validateConfig();

        return $this->buildStorage();
    }

    /**
     * @return void
     */
    protected function validateMandatoryConfigFields()
    {
        $this->configTransfer->requireName();
        $this->configTransfer->requireType();
        $this->configTransfer->requireData();
    }

}
