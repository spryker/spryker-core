<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model;

use Spryker\Zed\Propel\Business\Model\PropelSchemaWriter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelSchemaWriterTest
 * Add your own group annotations below this line
 */
class PropelSchemaWriterTest extends AbstractPropelSchemaTest
{
    /**
     * @var string
     */
    public const TEST_FILE_NAME = 'test_file';

    /**
     * @var string
     */
    public const TEST_CONTENT = 'some test content';

    /**
     * @return void
     */
    public function testWriteMustWriteContentToFile(): void
    {
        $writer = new PropelSchemaWriter(new Filesystem(), $this->getFixtureDirectory());
        $this->assertFalse(file_exists($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . static::TEST_FILE_NAME));
        $writer->write(static::TEST_FILE_NAME, static::TEST_CONTENT);
        $this->assertTrue(file_exists($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . static::TEST_FILE_NAME));
        $this->assertSame(
            static::TEST_CONTENT,
            file_get_contents($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . static::TEST_FILE_NAME),
        );
    }
}
