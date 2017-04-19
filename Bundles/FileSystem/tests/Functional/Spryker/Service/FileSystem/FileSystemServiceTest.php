<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Service\FileSystem;

use FileSystem\Stub\FileSystemConfigStub;
use PHPUnit_Framework_TestCase;
use Spryker\Service\FileSystem\FileSystemService;
use Spryker\Service\FileSystem\FileSystemServiceFactory;

/**
 * @group Functional
 * @group Spryker
 * @group Service
 * @group FileSystem
 * @group FileSystemServiceTest
 */
class FileSystemServiceTest extends PHPUnit_Framework_TestCase
{

    const RESOURCE_FILE_NAME = 'fileName.jpg';
    const STORAGE_PRODUCT_IMAGE = 'productImage';
    const STORAGE_CUSTOMER_IMAGE = 'customerImage';

    /**
     * @var \Spryker\Service\FileSystem\FileSystemServiceInterface
     */
    protected $fileSystemService;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $config = new FileSystemConfigStub();

        $factory = new FileSystemServiceFactory();
        $factory->setConfig($config);

        $this->fileSystemService = new FileSystemService();
        $this->fileSystemService->setFactory($factory);
    }

    /**
     * @return void
     */
    public function testGetMimeTypeByFilename()
    {
        $mimeType = $this->fileSystemService->getMimeTypeByFilename(static::RESOURCE_FILE_NAME);

        $this->assertSame('image/jpeg', $mimeType);
    }

    /**
     * @return void
     */
    public function testGetExtensionByFilename()
    {
        $extension = $this->fileSystemService->getExtensionByFilename(static::RESOURCE_FILE_NAME);

        $this->assertSame('jpg', $extension);
    }

    /**
     * @return void
     */
    public function testGetStorageByName()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_PRODUCT_IMAGE);
        $this->assertSame(static::STORAGE_PRODUCT_IMAGE, $storage->getName());

        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_CUSTOMER_IMAGE);
        $this->assertSame(static::STORAGE_CUSTOMER_IMAGE, $storage->getName());
    }

}
