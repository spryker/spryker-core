<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Shared\Url\UrlConfig;
use Spryker\Zed\Product\Business\ProductBusinessFactory;
use Spryker\Zed\Product\ProductConfig;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group UrlHandlingTest
 * Add your own group annotations below this line
 */
class UrlHandlingTest extends FacadeTestAbstract
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $configMock = $this->createMock(ProductConfig::class);
        $configMock->method('isFullLocaleNamesInUrlEnabled')->willReturn(true);
        $productBusinessFactory = new ProductBusinessFactory();
        $productBusinessFactory->setConfig($configMock);
        $this->productFacade->setFactory($productBusinessFactory);
    }

        /**
         * @return void
         */
    public function testCreateProductUrlShouldCreateNewUrlForProductAbstract(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en-us/product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de-de/product-name-dede-' . $idProductAbstract);

        $productUrl = $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrl, $expectedENUrl);
        $this->assertProductUrl($productUrl, $expectedDEUrl);
    }

    /**
     * @return void
     */
    public function testCreateProductUrlShouldCreateNewUrlForProductAbstractBCCheck(): void
    {
        $configMock = $this->createMock(ProductConfig::class);
        $configMock->method('isFullLocaleNamesInUrlEnabled')->willReturn(false);
        $productBusinessFactory = new ProductBusinessFactory();
        $productBusinessFactory->setConfig($configMock);
        $this->productFacade->setFactory($productBusinessFactory);
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en/product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de/product-name-dede-' . $idProductAbstract);

        $productUrl = $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrl, $expectedENUrl);
        $this->assertProductUrl($productUrl, $expectedDEUrl);
    }

    /**
     * @return void
     */
    public function testUpdateProductsUrlShouldCreateUrlsForProductAbstracts(): void
    {
        // Arrange
        $firstProductAbstractTransfer = clone $this->productAbstractTransfer;
        $firstProductAbstractTransfer->setSku($firstProductAbstractTransfer->getSku() . '_first');
        $secondProductAbstractTransfer = clone $this->productAbstractTransfer;
        $secondProductAbstractTransfer->setSku($firstProductAbstractTransfer->getSku() . '_second');
        $firstProductAbstractTransfer->setIdProductAbstract(
            $this->productAbstractManager->createProductAbstract($firstProductAbstractTransfer),
        );
        $secondProductAbstractTransfer->setIdProductAbstract(
            $this->productAbstractManager->createProductAbstract($secondProductAbstractTransfer),
        );
        $productAbstractTransfers = [
            $firstProductAbstractTransfer->getIdProductAbstract() => $firstProductAbstractTransfer,
            $secondProductAbstractTransfer->getIdProductAbstract() => $secondProductAbstractTransfer,
        ];

        // Act
        $urlTransfers = $this->productFacade->updateProductsUrl($productAbstractTransfers);

        $urlTransfersGroupedByAbstractId = [];
        foreach ($urlTransfers as $urlTransfer) {
            $urlTransfersGroupedByAbstractId[$urlTransfer->getFkResourceProductAbstract()][$urlTransfer->getFkLocale()] = $urlTransfer;
        }

        // Assert
        $this->assertArrayHasKey($firstProductAbstractTransfer->getIdProductAbstract(), $urlTransfersGroupedByAbstractId);
        $this->assertArrayHasKey($secondProductAbstractTransfer->getIdProductAbstract(), $urlTransfersGroupedByAbstractId);
        $this->assertCount(2, $urlTransfersGroupedByAbstractId[$secondProductAbstractTransfer->getIdProductAbstract()]);
        $this->assertCount(2, $urlTransfersGroupedByAbstractId[$firstProductAbstractTransfer->getIdProductAbstract()]);

        $firstProductAbstractUrlTransfers = $urlTransfersGroupedByAbstractId[$firstProductAbstractTransfer->getIdProductAbstract()];
        $this->assertSame(
            $firstProductAbstractUrlTransfers[$this->locales[static::EN_LOCALE]->getIdLocale()]->getUrl(),
            '/en-us/product-name-enus-' . $firstProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $firstProductAbstractUrlTransfers[$this->locales[static::EN_LOCALE]->getIdLocale()]->getFkResourceProductAbstract(),
            $firstProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $firstProductAbstractUrlTransfers[$this->locales[static::DE_LOCALE]->getIdLocale()]->getUrl(),
            '/de-de/product-name-dede-' . $firstProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $firstProductAbstractUrlTransfers[$this->locales[static::DE_LOCALE]->getIdLocale()]->getFkResourceProductAbstract(),
            $firstProductAbstractTransfer->getIdProductAbstract(),
        );

        $secondProductAbstractUrlTransfers = $urlTransfersGroupedByAbstractId[$secondProductAbstractTransfer->getIdProductAbstract()];
        $this->assertSame(
            $secondProductAbstractUrlTransfers[$this->locales[static::EN_LOCALE]->getIdLocale()]->getUrl(),
            '/en-us/product-name-enus-' . $secondProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $secondProductAbstractUrlTransfers[$this->locales[static::EN_LOCALE]->getIdLocale()]->getFkResourceProductAbstract(),
            $secondProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $secondProductAbstractUrlTransfers[$this->locales[static::DE_LOCALE]->getIdLocale()]->getUrl(),
            '/de-de/product-name-dede-' . $secondProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $secondProductAbstractUrlTransfers[$this->locales[static::DE_LOCALE]->getIdLocale()]->getFkResourceProductAbstract(),
            $secondProductAbstractTransfer->getIdProductAbstract(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateProductUrlShouldSaveUrlForProductAbstract(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en-us/new-product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de-de/new-product-name-dede-' . $idProductAbstract);

        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName('New ' . $localizedAttribute->getName());
        }

        $productUrl = $this->productFacade->updateProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrl, $expectedENUrl);
        $this->assertProductUrl($productUrl, $expectedDEUrl);
    }

    /**
     * @return void
     */
    public function testDeleteProductUrlShouldDeleteUrlForProductAbstract(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productFacade->createProductUrl($this->productAbstractTransfer);
        $this->productFacade->deleteProductUrl($this->productAbstractTransfer);

        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);
        $this->assertGreaterThan(0, count($productUrlTransfer->getUrls()));

        foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
            $this->assertNull($localizedUrlTransfer->getUrl());
        }
    }

    /**
     * @return void
     */
    public function testCreateUrlShouldThrowExceptionWhenUrlExists(): void
    {
        $this->expectException(UrlExistsException::class);

        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productFacade->createProductUrl($this->productAbstractTransfer);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateUrlShouldNotThrowExceptionWhenUrlExistsForSameProduct(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productFacade->createProductUrl($this->productAbstractTransfer);
        $this->productFacade->updateProductUrl($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testProductUrlShouldBeUnique(): void
    {
        $this->expectException(UrlExistsException::class);

        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productFacade->updateProductUrl($this->productAbstractTransfer);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteProductUrlCanBeExecutedWhenUrlDoesNotExist(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productFacade->deleteProductUrl($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductUrl(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en-us/product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de-de/product-name-dede-' . $idProductAbstract);

        $productUrl = $this->productFacade->getProductUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
        $this->assertProductUrl($productUrl, $expectedENUrl);
        $this->assertProductUrl($productUrl, $expectedDEUrl);
    }

    /**
     * @return void
     */
    public function testTouchProductUrlActiveShouldTouchLogic(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productUrlManager->createProductUrl($this->productAbstractTransfer);

        $this->productFacade->touchProductAbstractUrlActive($this->productAbstractTransfer);

        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);
        $this->assertGreaterThan(0, count($productUrlTransfer->getUrls()));

        foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer->setUrl($localizedUrlTransfer->getUrl());
            $urlTransfer = $this->urlFacade->findUrlCaseInsensitive($urlTransfer);

            $this->tester->assertTouchActive(UrlConfig::RESOURCE_TYPE_URL, $urlTransfer->getIdUrl());
        }
    }

    /**
     * @return void
     */
    public function testTouchProductUrlDeletedShouldTouchLogic(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->productFacade->touchProductAbstractUrlDeleted($this->productAbstractTransfer);

        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);
        $this->assertGreaterThan(0, count($productUrlTransfer->getUrls()));

        foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer->setUrl($localizedUrlTransfer->getUrl());
            $urlTransfer = $this->urlFacade->findUrlCaseInsensitive($urlTransfer);

            $this->tester->assertTouchDeleted(UrlConfig::RESOURCE_TYPE_URL, $urlTransfer->getIdUrl());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductUrlTransfer $productUrl
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $expectedUrl
     *
     * @return void
     */
    protected function assertProductUrl(ProductUrlTransfer $productUrl, LocalizedUrlTransfer $expectedUrl): void
    {
        $this->assertSame($productUrl->getAbstractSku(), $productUrl->getAbstractSku());

        $urls = [];
        foreach ($productUrl->getUrls() as $actualUrlTransfer) {
            $urls[$actualUrlTransfer->getLocale()->getLocaleName()] = $actualUrlTransfer->getUrl();
        }

        $this->assertArrayHasKey($expectedUrl->getLocale()->getLocaleName(), $urls);
        $this->assertSame($expectedUrl->getUrl(), $urls[$expectedUrl->getLocale()->getLocaleName()]);
    }
}
