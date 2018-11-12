<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryImage\Business;

use Codeception\Test\Unit;
use Spryker\Zed\CategoryImage\Business\Model\ImageSetLocalizer;
use Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CategoryImage
 * @group Business
 * @group ImageSetLocalizerTest
 * Add your own group annotations below this line
 */
class ImageSetLocalizerTest extends Unit
{
    public const VALID_LOCALE_1_NAME = 'de_DE';
    public const VALID_LOCALE_2_NAME = 'de_US';
    public const INVALID_LOCALE_NAME = 'invalid-locale';

    /**
     * @var \SprykerTest\Zed\CategoryImage\CategoryImageBusinessTester
     */
    protected $tester;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface
     */
    protected $localeProviderStub;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Model\ImageSetLocalizerInterface
     */
    protected $imageSetLocalizer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->localeProviderStub = $this->createLocaleProviderStub();
        $this->imageSetLocalizer = new ImageSetLocalizer(
            $this->localeProviderStub
        );
    }

    /**
     * @dataProvider buildCategoryImageSetCollectionDataProvider
     *
     * @param string[] $localeCollection
     * @param int $expectedCount
     *
     * @return void
     */
    public function testBuildCategoryImageSetCollection(array $localeCollection, int $expectedCount): void
    {
        $categoryImageSetCollection = $this->buildImageSetTransferCollectionByLocales($localeCollection);
        $formImageSetCollection = $this->tester->buildFormImageSets($categoryImageSetCollection);
        $result = $this->imageSetLocalizer->buildCategoryImageSetCollection($formImageSetCollection);

        $this->assertCount($expectedCount, $result);
    }

    /**
     * @return void
     */
    public function testBuildFormImageSetCollection(): void
    {
        $localeCollection = [
            static::VALID_LOCALE_1_NAME,
            static::VALID_LOCALE_2_NAME,
        ];
        $categoryImageSetCollection = $this->buildImageSetTransferCollectionByLocales($localeCollection);

        $result = $this->imageSetLocalizer->buildFormImageSetCollection($categoryImageSetCollection);
        $this->assertFormImageSetCollectionIsValid($result, $localeCollection);
    }

    /**
     * @return array
     */
    public function buildCategoryImageSetCollectionDataProvider(): array
    {
        return [
            'one valid locale' => [[static::VALID_LOCALE_1_NAME], 1],
            'two valid locales' => [[static::VALID_LOCALE_1_NAME, static::VALID_LOCALE_2_NAME], 2],
            'one valid, one invalid locales' => [[static::VALID_LOCALE_1_NAME, static::INVALID_LOCALE_NAME], 1],
        ];
    }

    /**
     * @param string[] $localeNameCollection
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    protected function buildImageSetTransferCollectionByLocales(array $localeNameCollection): array
    {
        $imageSetTransferCollection = [];
        foreach ($localeNameCollection as $localeName) {
            $imageSetTransferCollection[] = $this->tester->buildCategoryImageSetTransfer([
                'localeName' => $localeName,
            ]);
        }

        return $imageSetTransferCollection;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface
     */
    protected function createLocaleProviderStub()
    {
        $localeProvider = $this->getMockBuilder(LocaleProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $localeProvider->method('getLocaleCollection')
            ->willReturn([
                $this->tester->buildLocaleTransfer(['localeName' => static::VALID_LOCALE_1_NAME]),
                $this->tester->buildLocaleTransfer(['localeName' => static::VALID_LOCALE_2_NAME]),

            ]);

        return $localeProvider;
    }

    /**
     * @param array $result
     * @param array $localeCollection
     *
     * @return void
     */
    protected function assertFormImageSetCollectionIsValid(array $result, array $localeCollection): void
    {
        $this->assertEquals(count($localeCollection), count($result));
        foreach ($localeCollection as $localeName) {
            $this->assertArrayHasKey($localeName, $result);
            /** @var \Generated\Shared\Transfer\CategoryImageSetTransfer $imageSetTransfer */
            foreach ($result[$localeName] as $imageSetTransfer) {
                $this->assertEquals($localeName, $imageSetTransfer->getLocale()->getLocaleName());
            }
        }
    }
}
