<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\OpenApi3;

use Codeception\Test\Unit;
use Spryker\Glue\Testify\OpenApi3\Mapper;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferenceResolver;
use SprykerTest\Glue\Testify\OpenApi3\Stub\Document;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Testify
 * @group OpenApi3
 * @group OpenApi3MapperTest
 * Add your own group annotations below this line
 */
class OpenApi3MapperTest extends Unit
{
    /**
     * @return void
     */
    public function testMapperShouldBeAbleToMapCollectionAndPrimitives(): void
    {
        $document = new Document();
        $referenceContainer = new ReferenceResolver($document);
        $mapper = new Mapper($referenceContainer);

        $payload = json_decode(json_encode([
            'foo1' => [
                'bar' => [
                    'value 1',
                    'value 2',
                    'value 3',
                ],
            ],
            'foo2' => [
                'bar' => [
                    'key1' => 'value 4',
                    'key2' => 'value 5',
                    'key3' => 'value 6',
                ],
            ],
        ]), false);

        $mapper->mapObjectFromPayload($document, $payload);

        $this->assertSame('value 1', $document->foo1->bar[0]);
        $this->assertSame('value 6', $document->foo2->bar['key3']);
        $this->assertEquals(['value 1', 'value 2', 'value 3'], $document->foo1->bar->toArray());
    }

    /**
     * @return void
     */
    public function testMapperShouldBeAbleToMapReferences(): void
    {
        $document = new Document();
        $referenceContainer = new ReferenceResolver($document);
        $mapper = new Mapper($referenceContainer);

        $payload = json_decode(json_encode([
            'foo1' => [
                'bar' => [
                    'value 1',
                    'value 2',
                    'value 3',
                ],
            ],
            'foo2' => [
                '$ref' => '#/foo1',
            ],
        ]), false);

        $mapper->mapObjectFromPayload($document, $payload);

        $this->assertEquals($document->foo1->bar->toArray(), $document->foo2->bar->toArray());
        $this->assertSame('value 2', $document->foo2->bar[1]);
    }
}
