<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentFile;

use Spryker\Client\ContentFile\Dependency\Client\ContentFileToContentStorageClientInterface;
use Spryker\Client\ContentFile\Executor\ContentFileListTermExecutorInterface;
use Spryker\Client\ContentFile\Executor\ContentFileListTermToFileListListTypeExecutor;
use Spryker\Client\ContentFile\Mapper\ContentFileListTypeMapper;
use Spryker\Client\ContentFile\Mapper\ContentFileListTypeMapperInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\ContentFile\ContentFileConfig;

class ContentFileFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentFile\Mapper\ContentFileListTypeMapperInterface
     */
    public function createContentFileListTypeMapper(): ContentFileListTypeMapperInterface
    {
        return new ContentFileListTypeMapper(
            $this->getContentStorageClient(),
            $this->getContentFileTermExecutorMap()
        );
    }

    /**
     * @return \Spryker\Client\ContentFile\Executor\ContentFileListTermExecutorInterface[]
     */
    public function getContentFileTermExecutorMap(): array
    {
        return [
            ContentFileConfig::CONTENT_TERM_FILE_LIST => $this->createFileListTermToFileListTypeExecutor(),
        ];
    }

    /**
     * @return \Spryker\Client\ContentFile\Executor\ContentFileListTermExecutorInterface
     */
    public function createFileListTermToFileListTypeExecutor(): ContentFileListTermExecutorInterface
    {
        return new ContentFileListTermToFileListListTypeExecutor();
    }

    /**
     * @return \Spryker\Client\ContentFile\Dependency\Client\ContentFileToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentFileToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentFileDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}
