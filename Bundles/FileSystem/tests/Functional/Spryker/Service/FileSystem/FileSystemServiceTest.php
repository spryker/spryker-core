<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Service\FileSystem;

use PHPUnit_Framework_TestCase;
use Spryker\Service\FileSystem\FileSystemService;

/**
 * @group Functional
 * @group Spryker
 * @group Service
 * @group FileSystem
 * @group FileSystemServiceTest
 */
class FileSystemServiceTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetMimeTypeByFilename()
    {
        $fileSystemService = new FileSystemService();
        $mimeType = $fileSystemService->getMimeTypeByFilename('fileName.jpg');

        $this->assertSame('image/jpeg', $mimeType);
    }

}
