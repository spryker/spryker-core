<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Attribute;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Spryker\Zed\Product\Business\Attribute\AttributeMerger;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Attribute
 * @group AttributeMergerTest
 * Add your own group annotations below this line
 */
class AttributeMergerTest extends Unit
{
    public const ID_LOCALE = 1;

    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeMergerInterface
     */
    protected $attributeMerger;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->attributeMerger = new AttributeMerger();
    }

    /**
     * @return array
     */
    public function getCombinedConcreteAttributesDataProvider()
    {
        return [
            'empty attributes' => $this->getEmptyAttributesData(),
            'concrete attributes' => $this->getConcreteAttributesData(),
            'localized concrete attributes' => $this->getLocalizedConcreteAttributesData(),
            'abstract attributes' => $this->getAbstractAttributesData(),
            'localized abstract attributes' => $this->getLocalizedAbstractAttributesData(),
        ];
    }

    /**
     * @dataProvider getCombinedConcreteAttributesDataProvider
     *
     * @param \Generated\Shared\Transfer\RawProductAttributesTransfer $rawProductAttributesTransfer
     * @param array $expectedAttributes
     *
     * @return void
     */
    public function testGetCombinedAttributesReturnsCorrectAttributeMergeResults(RawProductAttributesTransfer $rawProductAttributesTransfer, array $expectedAttributes)
    {
        $actualAttributes = $this->attributeMerger->merge($rawProductAttributesTransfer);

        $this->assertEquals($expectedAttributes, $actualAttributes);
    }

    /**
     * @return array
     */
    protected function getEmptyAttributesData()
    {
        $expectedAttributes = [];

        return [new RawProductAttributesTransfer(), $expectedAttributes];
    }

    /**
     * @return array
     */
    protected function getConcreteAttributesData()
    {
        $expectedAttributes = [
            'foo' => 'Foo',
        ];

        $rawProductAttributesTransfer = new RawProductAttributesTransfer();
        $rawProductAttributesTransfer->setConcreteAttributes([
            'foo' => 'Foo',
        ]);

        return [$rawProductAttributesTransfer, $expectedAttributes];
    }

    /**
     * @return array
     */
    protected function getLocalizedConcreteAttributesData()
    {
        $expectedAttributes = [
            'foo' => 'Foo - localized',
            'bar' => 'Bar',
        ];

        $rawProductAttributesTransfer = new RawProductAttributesTransfer();
        $rawProductAttributesTransfer
            ->setConcreteAttributes([
                'foo' => 'Foo',
                'bar' => 'Bar',
            ])
            ->setConcreteLocalizedAttributes([
                'foo' => 'Foo - localized',
            ]);

        return [$rawProductAttributesTransfer, $expectedAttributes];
    }

    /**
     * @return array
     */
    protected function getAbstractAttributesData()
    {
        $expectedAttributes = [
            'foo' => 'Foo - concrete',
            'bar' => 'Bar',
            'baz' => 'Baz',
        ];

        $rawProductAttributesTransfer = new RawProductAttributesTransfer();
        $rawProductAttributesTransfer
            ->setConcreteAttributes([
                'foo' => 'Foo - concrete',
                'bar' => 'Bar',
            ])
            ->setAbstractAttributes([
                'foo' => 'Foo',
                'baz' => 'Baz',
            ]);

        return [$rawProductAttributesTransfer, $expectedAttributes];
    }

    /**
     * @return array
     */
    protected function getLocalizedAbstractAttributesData()
    {
        $expectedAttributes = [
            'foo' => 'Foo - localized',
            'bar' => 'Bar',
            'baz' => 'Baz - localized',
            'waz' => 'Waz',
        ];

        $rawProductAttributesTransfer = new RawProductAttributesTransfer();
        $rawProductAttributesTransfer
            ->setConcreteAttributes([
                'foo' => 'Foo',
                'bar' => 'Bar',
            ])
            ->setConcreteLocalizedAttributes([
                'foo' => 'Foo - localized',
            ])
            ->setAbstractAttributes([
                'foo' => 'Foo - abstract',
                'waz' => 'Waz',
            ])
            ->setAbstractLocalizedAttributes([
                'baz' => 'Baz - localized',
            ]);

        return [$rawProductAttributesTransfer, $expectedAttributes];
    }
}
