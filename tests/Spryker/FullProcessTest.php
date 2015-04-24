<?php

use Spryker\ClassDefinition;
use Spryker\ClassCollectionManager;
use External\Sofee\SofeeXmlParser;
use Spryker\ClassGenerator;

class FullProcessTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $xmlTree;

    /**
     * @var Spryker\ClassCollectionManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new ClassCollectionManager();

        $fileContent = file_get_contents(dirname(__DIR__) . '/data/alfa.transfer.xml');

        $xml = new SofeeXmlParser();
        $xml->parseString($fileContent);

        $this->xmlTree = $xml->getTree();

        foreach ($this->xmlTree['transfers']['transfer'] as $item) {
            $this->manager->setClassDefinition($item);
        }

        $defs = $this->manager->getCollections();

        $generator = new ClassGenerator();
        $generator->setTargetFolder(dirname(__DIR__) . '/target/');
        foreach ($defs as $classDefinition) {
            $phpCode = $generator->generateClass($classDefinition);
            $target = dirname(__DIR__) . '/';
            file_put_contents($target, $phpCode);
        }
    }


    public function test_first()
    {
        $this->assertGreaterThan(0, count($this->xmlTree));
    }
}
