<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantFile\Business\File\FileWriteHandler;
use Spryker\Zed\MerchantFile\Business\File\FileWriteHandlerInterface;
use Spryker\Zed\MerchantFile\Business\File\Validation\MerchantFileValidator;
use Spryker\Zed\MerchantFile\Business\File\Validation\MerchantFileValidatorInterface;
use Spryker\Zed\MerchantFile\Business\File\Validation\Validator\FileContentTypeValidator;
use Spryker\Zed\MerchantFile\Business\File\Validation\Validator\ValidatorInterface;
use Spryker\Zed\MerchantFile\Business\File\Writer\FileWriter;
use Spryker\Zed\MerchantFile\Business\File\Writer\FileWriterInterface;
use Spryker\Zed\MerchantFile\Business\MerchantFile\Expander\MerchantFileExpander;
use Spryker\Zed\MerchantFile\Business\MerchantFile\Expander\MerchantFileExpanderInterface;
use Spryker\Zed\MerchantFile\Business\MerchantFile\Reader\MerchantFileReader;
use Spryker\Zed\MerchantFile\Business\MerchantFile\Reader\MerchantFileReaderInterface;
use Spryker\Zed\MerchantFile\Business\MerchantFile\Writer\MerchantFileWriter;
use Spryker\Zed\MerchantFile\Business\MerchantFile\Writer\MerchantFileWriterInterface;
use Spryker\Zed\MerchantFile\Dependency\Facade\MerchantFileToMerchantUserInterface;
use Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface;
use Spryker\Zed\MerchantFile\MerchantFileDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantFile\MerchantFileConfig getConfig()
 * @method \Spryker\Zed\MerchantFile\Persistence\MerchantFileEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantFile\Persistence\MerchantFileRepositoryInterface getRepository()()
 */
class MerchantFileBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantFile\Business\File\FileWriteHandlerInterface
     */
    public function createFileWriteHandler(): FileWriteHandlerInterface
    {
        return new FileWriteHandler(
            $this->createMerchantFileValidator(),
            $this->createMerchantFileWriter(),
            $this->createMerchantFileExpander(),
            $this->createUploader(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantFile\Business\File\Validation\MerchantFileValidatorInterface
     */
    public function createMerchantFileValidator(): MerchantFileValidatorInterface
    {
        return new MerchantFileValidator($this->createFileContentTypeValidator(), $this->getMerchantFileValidationPlugins());
    }

    /**
     * @return \Spryker\Zed\MerchantFile\Business\File\Validation\Validator\ValidatorInterface
     */
    public function createFileContentTypeValidator(): ValidatorInterface
    {
        return new FileContentTypeValidator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\MerchantFile\Business\File\Writer\FileWriterInterface
     */
    public function createUploader(): FileWriterInterface
    {
        return new FileWriter(
            $this->getFileSystemService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantFile\Business\MerchantFile\Writer\MerchantFileWriterInterface
     */
    public function createMerchantFileWriter(): MerchantFileWriterInterface
    {
        return new MerchantFileWriter(
            $this->getEntityManager(),
            $this->getMerchantFilePostSavePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantFile\Business\MerchantFile\Reader\MerchantFileReaderInterface
     */
    public function createMerchantFileReader(): MerchantFileReaderInterface
    {
        return new MerchantFileReader(
            $this->getRepository(),
            $this->getConfig(),
            $this->getFileSystemService(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantFile\Business\MerchantFile\Expander\MerchantFileExpanderInterface
     */
    public function createMerchantFileExpander(): MerchantFileExpanderInterface
    {
        return new MerchantFileExpander(
            $this->getMerchantUserFacade(),
        );
    }

    /**
     * @return array<\Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFilePostSavePluginInterface>
     */
    public function getMerchantFilePostSavePlugins(): array
    {
        return $this->getProvidedDependency(MerchantFileDependencyProvider::PLUGINS_MERCHANT_FILE_POST_SAVE);
    }

    /**
     * @return array<\Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFileValidationPluginInterface>
     */
    public function getMerchantFileValidationPlugins(): array
    {
        return $this->getProvidedDependency(MerchantFileDependencyProvider::PLUGINS_MERCHANT_FILE_VALIDATION);
    }

    /**
     * @return \Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface
     */
    public function getFileSystemService(): MerchantFileToFileSystemServiceInterface
    {
        return $this->getProvidedDependency(MerchantFileDependencyProvider::SERVICE_FILE_SYSTEM);
    }

    /**
     * @return \Spryker\Zed\MerchantFile\Dependency\Facade\MerchantFileToMerchantUserInterface
     */
    public function getMerchantUserFacade(): MerchantFileToMerchantUserInterface
    {
        return $this->getProvidedDependency(MerchantFileDependencyProvider::FACADE_MERCHANT_USER);
    }
}
