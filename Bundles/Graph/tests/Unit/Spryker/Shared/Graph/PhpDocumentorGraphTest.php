<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Graph;

use Spryker\Shared\Graph\PhpDocumentorGraph;

/**
 * @group Spryker
 * @group Tool
 * @group GraphPhpDocumentor
 */
class PhpDocumentorGraphTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getTypes
     *
     * @param string $type
     *
     * @return void
     */
    public function testSetTypeMustAlsoAllowStrictTypes($type)
    {
        $graph = new PhpDocumentorGraph();

        $this->assertInstanceOf(PhpDocumentorGraph::class, $graph->setType($type));
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return [
            ['digraph'],
            ['strict digraph'],
            ['graph'],
            ['strict graph'],
            ['subgraph'],
            ['strict subgraph'],
        ];
    }

    /**
     * @return void
     */
    public function testSetDisAllowedTypeMustThrowException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $graph = new PhpDocumentorGraph();
        $graph->setType('wrong type');
    }

}
