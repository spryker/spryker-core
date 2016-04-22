<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Collector\Business\Exporter\Writer\File;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterBuilder;

class FileWriterBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testFileWriterBuilderShouldReturnInstance()
    {
        $builder = new FileWriterBuilder('/test/export/dir');
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName('de_DE');

        $this->assertInstanceOf(
            'Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriter',
            $builder->build('test', $localeTransfer)
        );
    }

    /**
     * @return void
     */
    public function testGetFullExportPath()
    {
        $builder = new FileWriterBuilder('/test/export/dir');
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName('de_DE');

        $this->assertEquals(
            '/test/export/dir/test_de_DE.csv',
            $builder->getFullExportPath('test', $localeTransfer)
        );
    }

}
