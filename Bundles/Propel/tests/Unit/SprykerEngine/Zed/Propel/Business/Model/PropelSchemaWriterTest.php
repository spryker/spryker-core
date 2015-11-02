<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Propel\Business\Model;

use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaWriter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelSchemaWriter
 */
class PropelSchemaWriterTest extends AbstractPropelSchemaTest
{

    const TEST_FILE_NAME = 'test_file';
    const TEST_CONTENT = 'some test content';

    public function testWriteMustWriteContentToFile()
    {
        $writer = new PropelSchemaWriter(new Filesystem(), $this->getFixtureDirectory());
        $this->assertFalse(file_exists($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . self::TEST_FILE_NAME));
        $writer->write(self::TEST_FILE_NAME, self::TEST_CONTENT);
        $this->assertTrue(file_exists($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . self::TEST_FILE_NAME));
        $this->assertSame(
            self::TEST_CONTENT,
            file_get_contents($this->getFixtureDirectory() . DIRECTORY_SEPARATOR . self::TEST_FILE_NAME)
        );
    }

}
