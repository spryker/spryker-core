<?php

namespace Unit\SprykerEngine\Zed\Propel\Business\Model;

use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaMerger;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelSchemaMerger
 */
class PropelSchemaMergerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return string
     */
    private function getFixtureDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'PropelSchemaMerger';
    }

    public function testMergeTwoSchemaFilesMustReturnStringWithMergedContent()
    {
        $projectFile = new SplFileInfo(
            $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Project' . DIRECTORY_SEPARATOR . 'foo_bar.schema.xml',
            '',
            ''
        );
        $vendorFile = new SplFileInfo(
            $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Vendor' . DIRECTORY_SEPARATOR . 'foo_bar.schema.xml',
            '',
            ''
        );

        $filesToMerge = [];
        $filesToMerge['foo_bar.schema.xml'][] = $projectFile;
        $filesToMerge['foo_bar.schema.xml'][] = $vendorFile;

        $merger = new PropelSchemaMerger();
        $content = $merger->merge($filesToMerge['foo_bar.schema.xml']);

        echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($content) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
    }

}
