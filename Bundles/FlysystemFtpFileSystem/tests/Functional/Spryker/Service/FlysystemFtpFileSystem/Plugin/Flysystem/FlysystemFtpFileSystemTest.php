<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Service\FlysystemFtpFileSystem\Plugin\Flysystem;

use Generated\Shared\Transfer\FlysystemConfigFtpTransfer;
use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\FilesystemInterface;
use PHPUnit_Framework_TestCase;
use Spryker\Service\FlysystemFtpFileSystem\Plugin\Flysystem\FtpFilesystemBuilderPlugin;

/**
 * @group Functional
 * @group Spryker
 * @group Service
 * @group FlysystemFtpFileSystem
 * @group Plugin
 * @group Flysystem
 * @group FlysystemFtpFileSystemTest
 */
class FlysystemFtpFileSystemTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testFtpFilesystemBuilderPlugin()
    {
        $localFilesystemBuilderPlugin = new FtpFilesystemBuilderPlugin();

        $adapterConfigTransfer = new FlysystemConfigFtpTransfer();
        $adapterConfigTransfer->setHost('ftp://foo.bar');
        $adapterConfigTransfer->setUsername('foo@bar');
        $adapterConfigTransfer->setPassword('foobar');

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('FtpDocumentFilesystem');
        $configTransfer->setType(FtpFilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        $ftpFilesystem = $localFilesystemBuilderPlugin->build($configTransfer);

        $this->assertInstanceOf(FilesystemInterface::class, $ftpFilesystem);
    }

    /**
     * @return void
     */
    public function testFtpFilesystemBuilderPluginShouldAcceptType()
    {
        $localFilesystemBuilderPlugin = new FtpFilesystemBuilderPlugin();

        $adapterConfigTransfer = new FlysystemConfigFtpTransfer();
        $adapterConfigTransfer->setHost('ftp://foo.bar');
        $adapterConfigTransfer->setUsername('foo@bar');
        $adapterConfigTransfer->setPassword('foobar');

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('FtpDocumentFilesystem');
        $configTransfer->setType(FtpFilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        $isTypeAccepted = $localFilesystemBuilderPlugin->acceptType($configTransfer->getType());

        $this->assertTrue($isTypeAccepted);
    }

}
