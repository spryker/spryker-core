<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Product\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Product\Business\Attribute\AttributeProcessor;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Product
 * @group Business
 * @group Model
 * @group AttributeProcessorTest
 */
class AttributeProcessorTest extends Test
{

    /**
     * @var array
     */
    protected $abstractAttributes;

    /**
     * @var array
     */
    protected $abstractLocalizedAttributes;

    /**
     * @var array
     */
    protected $concreteAttributes;

    /**
     * @var array
     */
    protected $concreteLocalizedAttributes;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $deLocale;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $enLocale;

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface
     */
    protected $attributeProcessor;

    protected function setUp()
    {
        $this->deLocale = (new LocaleTransfer())
            ->setIdLocale(46)
            ->setLocaleName('de_DE');

        $this->enLocale = (new LocaleTransfer())
            ->setIdLocale(66)
            ->setLocaleName('en_US');

        $this->abstractAttributes = [
            'battery' => 8
        ];

        $this->concreteAttributes = [
            'size' => 'XL',
            'foo' => 'bar'
        ];

        $deAbstractLocalizedAttribute = [
            'material' => 'stein',
        ];

        $enAbstractLocalizedAttribute = [
            'color' => 'red',
        ];

        $this->abstractLocalizedAttributes = [
            'de_DE' => $deAbstractLocalizedAttribute,
            'en_US' => $enAbstractLocalizedAttribute
        ];

        $deConcreteLocalizedAttribute = [
            'color' => 'rot',
        ];

        $enConcreteLocalizedAttribute = [
            'material' => 'stone',
            'english_only' => true
        ];

        $this->concreteLocalizedAttributes = [
            'de_DE' => $deConcreteLocalizedAttribute,
            'en_US' => $enConcreteLocalizedAttribute
        ];

        $this->attributeProcessor = new AttributeProcessor(
            $this->abstractAttributes,
            $this->concreteAttributes,
            $this->abstractLocalizedAttributes,
            $this->concreteLocalizedAttributes
        );
    }

    public function testMergeAttributesWithoutLocalizedData()
    {
        $actual = $this->attributeProcessor->mergeAttributes();
        $expected = [
            'battery' => 8,
            'size' => 'XL',
            'foo' => 'bar',
        ];

        $this->assertEquals(
            $actual,
            $expected
        );
    }

    public function testMergeAttributesWihGermanLocale()
    {
        $actual = $this->attributeProcessor->mergeAttributes('de_DE');

        $expected = [
            'battery' => 8,
            'color' => 'rot',
            'foo' => 'bar',
            'material' => 'stein',
            'size' => 'XL'
        ];

        $this->assertEquals(
            $actual,
            $expected
        );
    }

    public function testMergeAttributesWihEnglishLocale()
    {
        $actual = $this->attributeProcessor->mergeAttributes('en_US');

        $expected = [
            'battery' => 8,
            'color' => 'red',
            'english_only' => true,
            'foo' => 'bar',
            'material' => 'stone',
            'size' => 'XL'
        ];

        $this->assertEquals(
            $actual,
            $expected
        );
    }

    public function testGetAllKeys()
    {
        $actual = $this->attributeProcessor->getAllKeys();

        $expected = [
            'material' => null,
            'color' => null,
            'english_only' => null,
            'battery' => null,
            'size' => null,
            'foo' => null,
        ];

        $this->assertEquals(
            $actual,
            $expected
        );
    }

}
