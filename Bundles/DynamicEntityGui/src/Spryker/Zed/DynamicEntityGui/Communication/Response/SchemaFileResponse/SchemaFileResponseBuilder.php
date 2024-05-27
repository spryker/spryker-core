<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Response\SchemaFileResponse;

use SplFileObject;
use Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToStorageFacadeInterface;
use Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SchemaFileResponseBuilder implements SchemaFileResponseBuilderInterface
{
    /**
     * @var string
     */
    protected const FILE_DATA = 'file_data';

    /**
     * @var string
     */
    protected const CONTENT_TYPE_HEADER_NAME = 'Content-Type';

    /**
     * @var string
     */
    protected const CONTENT_DISPOSITION_HEADER_NAME = 'Content-Disposition';

    /**
     * @var string
     */
    protected const CONTENT_TYPE_HEADER_VALUE = 'application/yaml';

    /**
     * @var string
     */
    protected const CONTENT_DISPOSITION_HEADER_VALUE = 'attachment; filename=%s';

    /**
     * @param \Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig $config
     * @param \Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToStorageFacadeInterface $storageFacade
     */
    public function __construct(
        protected DynamicEntityGuiConfig $config,
        protected DynamicEntityGuiToStorageFacadeInterface $storageFacade
    ) {
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function createResponse(): BinaryFileResponse
    {
        $backendApiSchemaStorageKey = $this->config->getBackendApiSchemaStorageKey();
        $schemaData = $this->storageFacade->get($backendApiSchemaStorageKey);

        $response = new BinaryFileResponse($this->createTemporaryFile($schemaData));
        $response->headers->set(static::CONTENT_TYPE_HEADER_NAME, static::CONTENT_TYPE_HEADER_VALUE);
        $response->headers->set(
            static::CONTENT_DISPOSITION_HEADER_NAME,
            sprintf(
                static::CONTENT_DISPOSITION_HEADER_VALUE,
                $this->config->getDownloadFileName(),
            ),
        );
        $response->deleteFileAfterSend(true);

        return $response;
    }

    /**
     * @param array<string, string> $schemaData
     *
     * @return \SplFileObject
     */
    protected function createTemporaryFile(array $schemaData): SplFileObject
    {
        $file = new SplFileObject('tmp_schema_file.yml', 'w+');
        $file->fwrite($schemaData[static::FILE_DATA]);

        return $file;
    }
}
