<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Transformer\MerchantProfileUrlCollectionDataTransformer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProfileMerchantPortalGui
 * @group Communication
 * @group Form
 * @group Transformer
 * @group MerchantProfileUrlCollectionDataTransformerTest
 * Add your own group annotations below this line
 */
class MerchantProfileUrlCollectionDataTransformerTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_URL = 'test-url';

    /**
     * @var string
     */
    protected const TEST_URL_2 = 'test-url-2';

    /**
     * @var string
     */
    protected const TEST_URL_BASE = 'url';

    /**
     * @var string
     */
    protected const TEST_URL_PREFIX = 'test-';

    /**
     * @dataProvider getTransformDataProvider
     *
     * @param mixed $value
     * @param list<\Generated\Shared\Transfer\UrlTransfer> $expectedUrlTransfers
     *
     * @return void
     */
    public function testTransform($value, array $expectedUrlTransfers): void
    {
        // Act
        $urlTransfers = $this->createMerchantProfileUrlCollectionDataTransformer()
            ->transform($value)
            ->getArrayCopy();

        // Assert
        $this->assertSameUrlTransfers($expectedUrlTransfers, $urlTransfers);
    }

    /**
     * @dataProvider getReverseTransformDataProvider
     *
     * @param mixed $value
     * @param list<\Generated\Shared\Transfer\UrlTransfer> $expectedUrlTransfers
     *
     * @return void
     */
    public function testReverseTransform($value, array $expectedUrlTransfers): void
    {
        // Act
        $urlTransfers = $this->createMerchantProfileUrlCollectionDataTransformer()
            ->reverseTransform($value)
            ->getArrayCopy();

        // Assert
        $this->assertSameUrlTransfers($expectedUrlTransfers, $urlTransfers);
    }

    /**
     * @return array<string, array>>
     */
    protected function getTransformDataProvider(): array
    {
        return [
            'Should return empty collection when null value provided' => [
                null, [],
            ],
            'Should return empty collection when empty value provided' => [
                '', [],
            ],
            'Should return empty collection when no iterable value provided' => [
                static::TEST_URL, [],
            ],
            'Should return collection when array is provided' => [
                [
                    (new UrlTransfer())->setUrl(static::TEST_URL)->setUrlPrefix(static::TEST_URL_PREFIX),
                    (new UrlTransfer())->setUrl(static::TEST_URL_2),
                    new UrlTransfer(),
                ],
                [
                    (new UrlTransfer())->setUrl(static::TEST_URL_BASE)->setUrlPrefix(static::TEST_URL_PREFIX),
                    (new UrlTransfer())->setUrl(static::TEST_URL_2),
                ],
            ],
            'Should return collection when ArrayObject is provided' => [
                new ArrayObject([
                    (new UrlTransfer())->setUrl(static::TEST_URL)->setUrlPrefix(static::TEST_URL_PREFIX),
                    (new UrlTransfer())->setUrl(static::TEST_URL_2),
                    new UrlTransfer(),
                ]),
                [
                    (new UrlTransfer())->setUrl(static::TEST_URL_BASE)->setUrlPrefix(static::TEST_URL_PREFIX),
                    (new UrlTransfer())->setUrl(static::TEST_URL_2),
                ],
            ],
        ];
    }

    /**
     * @return array<string, array>>
     */
    protected function getReverseTransformDataProvider(): array
    {
        return [
            'Should return empty collection when null value provided' => [
                null, [],
            ],
            'Should return empty collection when empty value provided' => [
                '', [],
            ],
            'Should return empty collection when no iterable value provided' => [
                static::TEST_URL, [],
            ],
            'Should return collection when array is provided' => [
                [
                    (new UrlTransfer())->setUrl(static::TEST_URL_BASE)->setUrlPrefix(static::TEST_URL_PREFIX),
                    (new UrlTransfer())->setUrl(static::TEST_URL_2)->setUrlPrefix(static::TEST_URL_PREFIX),
                    new UrlTransfer(),
                ],
                [
                    (new UrlTransfer())->setUrl(static::TEST_URL)->setUrlPrefix(static::TEST_URL_PREFIX),
                    (new UrlTransfer())->setUrl(static::TEST_URL_2)->setUrlPrefix(static::TEST_URL_PREFIX),
                    new UrlTransfer(),
                ],
            ],
            'Should return collection when ArrayObject is provided' => [
                new ArrayObject([
                    (new UrlTransfer())->setUrl(static::TEST_URL_BASE)->setUrlPrefix(static::TEST_URL_PREFIX),
                    (new UrlTransfer())->setUrl(static::TEST_URL_2)->setUrlPrefix(static::TEST_URL_PREFIX),
                    new UrlTransfer(),
                ]),
                [
                    (new UrlTransfer())->setUrl(static::TEST_URL)->setUrlPrefix(static::TEST_URL_PREFIX),
                    (new UrlTransfer())->setUrl(static::TEST_URL_2)->setUrlPrefix(static::TEST_URL_PREFIX),
                    new UrlTransfer(),
                ],
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Transformer\MerchantProfileUrlCollectionDataTransformer
     */
    protected function createMerchantProfileUrlCollectionDataTransformer(): MerchantProfileUrlCollectionDataTransformer
    {
        return new MerchantProfileUrlCollectionDataTransformer();
    }

    /**
     * @param list<\Generated\Shared\Transfer\UrlTransfer> $expectedUrlTransfers
     * @param list<\Generated\Shared\Transfer\UrlTransfer> $actualUrlTransfers
     *
     * @return void
     */
    protected function assertSameUrlTransfers(array $expectedUrlTransfers, array $actualUrlTransfers): void
    {
        $actualUrlTransfers = array_map(function (UrlTransfer $urlTransfer) {
            return $urlTransfer->toArray();
        }, $actualUrlTransfers);

        $expectedUrlTransfers = array_map(function (UrlTransfer $urlTransfer) {
            return $urlTransfer->toArray();
        }, $expectedUrlTransfers);

        $this->assertSame($actualUrlTransfers, $expectedUrlTransfers);
    }
}
