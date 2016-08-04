<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductImage\Business\Model;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessor;

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

        $deAbstractLocalizedAttribute = (new LocalizedAttributesTransfer())->fromArray([
            'locale' => $this->deLocale,
            'name' => 'name de',
            'description' => 'description de',
            'metaTitle' => 'metaTitle de',
            'metaDescription' => 'metaDescription de',
            'metaKeywords' => 'metaKeywords de',
            'attributes' => [
                'material' => 'stein',
            ],
        ]);

        $enAbstractLocalizedAttribute = (new LocalizedAttributesTransfer())->fromArray([
            'locale' => $this->enLocale,
            'name' => 'name en',
            'description' => 'description en',
            'metaTitle' => 'metaTitle en',
            'metaDescription' => 'metaDescription en',
            'metaKeywords' => 'metaKeywords en',
            'attributes' => [
                'color' => 'red',
            ],
        ]);

        $this->abstractLocalizedAttributes = [
            $deAbstractLocalizedAttribute, $enAbstractLocalizedAttribute
        ];

        $deConcreteLocalizedAttribute = (new LocalizedAttributesTransfer())->fromArray([
            'locale' => $this->deLocale,
            'name' => 'name de',
            'description' => 'description de',
            'attributes' => [
                'color' => 'rot',
            ],
        ]);

        $enConcreteLocalizedAttribute = (new LocalizedAttributesTransfer())->fromArray([
            'locale' => $this->enLocale,
            'name' => 'name en',
            'description' => 'description en',
            'attributes' => [
                'material' => 'stone',
            ],
        ]);

        $this->concreteLocalizedAttributes = [
            $deConcreteLocalizedAttribute, $enConcreteLocalizedAttribute
        ];
    }

    public function testMergeAttributesWithoutLocalizedData()
    {
        $attributeProcessor = new AttributeProcessor(
            $this->abstractAttributes,
            $this->concreteAttributes,
            $this->abstractLocalizedAttributes,
            $this->concreteLocalizedAttributes
        );

        $actual = $attributeProcessor->mergeAttributes();
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
        $attributeProcessor = new AttributeProcessor(
            $this->abstractAttributes,
            $this->concreteAttributes,
            $this->abstractLocalizedAttributes,
            $this->concreteLocalizedAttributes
        );

        $actual = $attributeProcessor->mergeAttributes('de_DE');

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
        $attributeProcessor = new AttributeProcessor(
            $this->abstractAttributes,
            $this->concreteAttributes,
            $this->abstractLocalizedAttributes,
            $this->concreteLocalizedAttributes
        );

        $actual = $attributeProcessor->mergeAttributes('en_US');

        $expected = [
            'battery' => 8,
            'color' => 'red',
            'foo' => 'bar',
            'material' => 'stone',
            'size' => 'XL'
        ];

        $this->assertEquals(
            $actual,
            $expected
        );
    }

}
