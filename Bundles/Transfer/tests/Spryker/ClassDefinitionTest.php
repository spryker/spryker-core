<?php

use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassDefinition;

class ClassDefinitionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $xmlTree;

    protected $definition;

    public function setUp()
    {
        $definition = new ClassDefinition('TestClass');
        $definition->setInterface([
            ['value' => 'Demo\AlfaInterface'],
            ['value' => 'Demo\BetaInterface'],
        ]);

        $definition->setProperty([
            'name' => 'id',
            'type' => 'int',
            'default' => '0',
        ]);
        $definition->setProperty([
            'name' => 'published',
            'type' => 'bool',
            'default' => true,
        ]);

        $this->definition = $definition;
    }


    public function test_class_name()
    {
        $this->assertSame('TestClassTransfer', $this->definition->getClassName());
    }

    public function test_interfaces_number()
    {
        $this->assertEquals(2, count($this->definition->getInterfaces()));
    }

    public function test_properties_number()
    {
        $this->assertEquals(2, count($this->definition->getProperties()));
    }

    /**
     * @dataProvider propertiesKeysProvider
     */
    public function test_properties_keys($key)
    {
        $this->assertArrayHasKey($key, $this->definition->getProperties());
    }

    /**
     * @return array
     */
    public function propertiesKeysProvider()
    {
        return [
            ['id'],
            ['published'],
        ];
    }
}
