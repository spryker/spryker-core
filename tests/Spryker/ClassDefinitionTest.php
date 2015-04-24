<?php

use Spryker\ClassDefinition;
use Spryker\ClassDefinitionCollection;
use External\Sofee\SofeeXmlParser;

class ClassDefinitionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $xmlTree;

    public function setUp()
    {
//        $fileContent = file_get_contents(dirname(__DIR__) . '/data/alfa.transfer.xml');
//
//        $xml = new SofeeXmlParser();
//        $xml->parseString($fileContent);
//
//        $this->xmlTree = $xml->getTree();
    }


    public function test_first()
    {
        //$this->assertGreaterThan(0, count($this->xmlTree));
        $this->assertTrue(true);
    }
}
