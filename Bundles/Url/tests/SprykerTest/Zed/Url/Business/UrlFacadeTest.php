<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Url\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use InvalidArgumentException;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Orm\Zed\Url\Persistence\SpyUrlRedirectQuery;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Url\Business\UrlFacade;
use Spryker\Zed\Url\Persistence\UrlQueryContainer;
use Spryker\Zed\Url\UrlConfig;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Url
 * @group Business
 * @group Facade
 * @group UrlFacadeTest
 * Add your own group annotations below this line
 */
class UrlFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const VALUE_URL = 'http://value.url/';

    /**
     * @var \SprykerTest\Zed\Url\UrlBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Url\Business\UrlFacade
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainer
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->urlFacade = new UrlFacade();
        $this->localeFacade = new LocaleFacade();
        $this->urlQueryContainer = new UrlQueryContainer();
        $this->touchQueryContainer = new TouchQueryContainer();
    }

    /**
     * @return void
     */
    public function testCreateUrlPersistsNewEntityToDatabase(): void
    {
        $urlQuery = $this->urlQueryContainer->queryUrls();
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');

        $urlTransfer = new UrlTransfer();
        $urlTransfer
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale());

        $urlCountBeforeCreation = $urlQuery->count();
        $newUrlTransfer = $this->urlFacade->createUrl($urlTransfer);
        $urlCountAfterCreation = $urlQuery->count();

        $this->assertGreaterThan(
            $urlCountBeforeCreation,
            $urlCountAfterCreation,
            'Number of url entities in database should be higher after creating new entity.',
        );

        $this->assertNotNull($newUrlTransfer->getIdUrl(), 'Returned transfer object should have url ID.');

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_URL, $newUrlTransfer->getIdUrl(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
        $this->assertSame(1, $touchQuery->count(), 'New entity should have active touch entry after creation.');
    }

    /**
     * @return void
     */
    public function testUpdateUrlPersistsChangedToDatabase(): void
    {
        $localeTransfer1 = $this->localeFacade->createLocale('ab_CD');
        $localeTransfer2 = $this->localeFacade->createLocale('ef_GH');

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/SoManyPageUrls')
            ->setFkLocale($localeTransfer1->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer
            ->setUrl('/SoManyPageUrls-2')
            ->setIdUrl($urlEntity->getIdUrl())
            ->setFkLocale($localeTransfer2->getIdLocale());

        $urlTransfer = $this->urlFacade->updateUrl($urlTransfer);

        $urlEntity = $this->urlQueryContainer
            ->queryUrl('/SoManyPageUrls-2')
            ->findOne();
        $this->assertInstanceOf(SpyUrl::class, $urlEntity, 'Url entity with new data should be in database after update.');
        $this->assertSame($urlTransfer->getFkLocale(), $urlEntity->getFkLocale(), 'Url entity should have updated locale ID.');

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_URL, $urlEntity->getIdUrl(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
        $this->assertSame(1, $touchQuery->count(), 'Url entity should have active touch entry after update.');
    }

    /**
     * @return void
     */
    public function testFindUrlEntityByUrl(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl('/some/url/like/string');

        $urlTransfer = $this->urlFacade->findUrlCaseInsensitive($urlTransfer);

        $this->assertNotNull($urlTransfer, 'Finding existing URL entity by path should return transfer object.');
        $this->assertSame($urlEntity->getIdUrl(), $urlTransfer->getIdUrl(), 'Reading URL entity by path should return transfer with proper data.');
    }

    /**
     * @return void
     */
    public function testFindUrlEntityById(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $urlTransfer = $this->urlFacade->findUrlCaseInsensitive($urlTransfer);

        $this->assertNotNull($urlTransfer, 'Finding existing URL entity by ID should return transfer object.');
        $this->assertSame($urlEntity->getUrl(), $urlTransfer->getUrl(), 'Reading URL entity by ID should return transfer with proper data.');
    }

    /**
     * @return void
     */
    public function testFindUrlCaseInsensitiveShouldSearchByIdAndReturnCorrectUrlTransfer(): void
    {
        // Assign
        $urlTransfer = $this->tester->haveUrl();

        // Act
        $existingUrlTransfer = $this->urlFacade->findUrlCaseInsensitive(
            (new UrlTransfer())->setIdUrl($urlTransfer->getIdUrl()),
        );

        // Assert
        $this->assertSame($urlTransfer->getUrl(), $existingUrlTransfer->getUrl());
    }

    /**
     * @return void
     */
    public function testFindUrlCaseInsensitiveShouldSearchByCaseInsensitiveUrlValueAndReturnCorrectUrlTransfer(): void
    {
        // Assign
        $urlTransfer = $this->tester->haveUrl();

        // Act
        $urlTransfer->setUrl(
            mb_strtoupper($urlTransfer->getUrl()),
        );
        $existingUrlTransfer = $this->urlFacade->findUrlCaseInsensitive($urlTransfer);

        // Assert
        $this->assertSame($urlTransfer->getIdUrl(), $existingUrlTransfer->getIdUrl());
    }

    /**
     * @return void
     */
    public function testFindUrlCaseInsensitiveWillThrowInvalidArgumentExceptionIfIdUrlOrUrlValueIsNotSet(): void
    {
        // Assign
        $urlTransfer = new UrlTransfer();

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $this->urlFacade->findUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @return void
     */
    public function testHasUrlEntityByUrl(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl('/some/url/like/string');

        $hasUrl = $this->urlFacade->hasUrl($urlTransfer);

        $this->assertTrue($hasUrl, 'Checking if URL entity exists by path should return true.');
    }

    /**
     * @return void
     */
    public function testHasUrlCaseInsensitiveShouldReturnTrueIfUrlExists(): void
    {
        // Assign
        $this->tester->haveUrl([
            UrlTransfer::URL => static::VALUE_URL,
        ]);

        $urlTransfer = (new UrlTransfer())
            ->setUrl(static::VALUE_URL);

        // Act
        $hasUrlCaseInsensitive = $this->urlFacade->hasUrlCaseInsensitive($urlTransfer);

        // Assert
        $this->assertTrue($hasUrlCaseInsensitive);
    }

    /**
     * @return void
     */
    public function testHasUrlCaseInsensitiveShouldReturnFalseIfUrlDoesNotExist(): void
    {
        // Assign
        $this->tester->haveUrl();

        $urlTransfer = (new UrlTransfer())
            ->setUrl(static::VALUE_URL);

        // Act
        $hasUrlCaseInsensitive = $this->urlFacade->hasUrlCaseInsensitive($urlTransfer);

        // Assert
        $this->assertFalse($hasUrlCaseInsensitive);
    }

    /**
     * @return void
     */
    public function testHasUrlCaseInsensitiveShouldReturnFalseIfUrlDoesNotExistButUrlRedirectExists(): void
    {
        // Assign
        $this->tester->haveUrlRedirect([], [
            UrlTransfer::URL => static::VALUE_URL,
        ]);

        $urlTransfer = (new UrlTransfer())
            ->setUrl(static::VALUE_URL);

        // Act
        $hasUrlCaseInsensitive = $this->urlFacade->hasUrlCaseInsensitive($urlTransfer);

        // Assert
        $this->assertFalse($hasUrlCaseInsensitive);
    }

    /**
     * @return void
     */
    public function testHasUrlCaseInsensitiveWillThrowInvalidArgumentExceptionIfIdUrlOrUrlValueIsNotSet(): void
    {
        // Assign
        $urlTransfer = new UrlTransfer();

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $this->urlFacade->hasUrl($urlTransfer);
    }

    /**
     * @return void
     */
    public function testHasUrlIgnoresRedirectedUrls(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl('/some/url/like/string');

        $hasUrl = $this->urlFacade->hasUrl($urlTransfer);

        $this->assertFalse($hasUrl, 'Checking if URL redirect entity exists should get ignored.');
    }

    /**
     * @return void
     */
    public function testHasUrlOrRedirectedUrlCaseInsensitiveShouldReturnTrueIfUrlExistsAndUrlRedirectDoesNot(): void
    {
        // Assign
        $this->tester->haveUrl([
            UrlTransfer::URL => static::VALUE_URL,
        ]);

        $urlTransfer = (new UrlTransfer())
            ->setUrl(static::VALUE_URL);

        // Act
        $hasUrlOrRedirectedUrlCaseInsensitive = $this->urlFacade->hasUrlOrRedirectedUrlCaseInsensitive($urlTransfer);

        // Assert
        $this->assertTrue($hasUrlOrRedirectedUrlCaseInsensitive);
    }

    /**
     * @return void
     */
    public function testHasUrlOrRedirectedUrlCaseInsensitiveShouldReturnTrueIfUrlRedirectExistsAndUrlDoesNot(): void
    {
        // Assign
        $this->tester->haveUrlRedirect([], [
            UrlTransfer::URL => static::VALUE_URL,
        ]);

        $urlTransfer = (new UrlTransfer())
            ->setUrl(static::VALUE_URL);

        // Act
        $hasUrlOrRedirectedUrlCaseInsensitive = $this->urlFacade->hasUrlOrRedirectedUrlCaseInsensitive($urlTransfer);

        // Assert
        $this->assertTrue($hasUrlOrRedirectedUrlCaseInsensitive);
    }

    /**
     * @return void
     */
    public function testHasUrlOrRedirectedUrlCaseInsensitiveShouldReturnFalseIfUrlAndUrlRedirectDoNotExist(): void
    {
        // Assign
        $urlTransfer = (new UrlTransfer())
            ->setUrl(static::VALUE_URL);

        // Act
        $hasUrlOrRedirectedUrlCaseInsensitive = $this->urlFacade->hasUrlOrRedirectedUrlCaseInsensitive($urlTransfer);

        // Assert
        $this->assertFalse($hasUrlOrRedirectedUrlCaseInsensitive);
    }

    /**
     * @return void
     */
    public function testHasUrlOrRedirectedUrlCaseInsensitiveWillThrowInvalidArgumentExceptionIfIdUrlOrUrlValueIsNotSet(): void
    {
        // Assign
        $urlTransfer = new UrlTransfer();

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $this->urlFacade->hasUrlOrRedirectedUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @return void
     */
    public function testHasUrlEntityById(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $hasUrl = $this->urlFacade->hasUrl($urlTransfer);

        $this->assertTrue($hasUrl, 'Checking if URL entity exists by ID should return true.');
    }

    /**
     * @return void
     */
    public function testHasUrlOrRedirectedUrlById(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $hasUrl = $this->urlFacade->hasUrlOrRedirectedUrlCaseInsensitive($urlTransfer);

        $this->assertTrue($hasUrl, 'Checking if URL redirect entity exists by ID should return true.');
    }

    /**
     * @return void
     */
    public function testDeleteUrlShouldRemoveEntityFromDatabase(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $urlQuery = SpyUrlQuery::create()->filterByIdUrl($urlEntity->getIdUrl());

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_URL, $urlTransfer->getIdUrl(), SpyTouchTableMap::COL_ITEM_EVENT_DELETED);

        $this->assertSame(1, $urlQuery->count(), 'Url entity should exist before deleting it.');
        $this->assertSame(0, $touchQuery->count(), 'Entity should not have deleted touch entry before deletion.');

        $this->urlFacade->deleteUrl($urlTransfer);

        $this->assertSame(0, $urlQuery->count(), 'Url entity should not exist after deleting it.');
        $this->assertSame(1, $touchQuery->count(), 'Entity should have deleted touch entry before deletion.');
    }

    /**
     * @return void
     */
    public function testDeleteUrlShouldRemoveRelatedUrlRedirectEntitiesFromDatabase(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/final/target/url')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlRedirectEntity1 = new SpyUrlRedirect();
        $urlRedirectEntity1
            ->setToUrl($urlEntity->getUrl())
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity1 = new SpyUrl();
        $urlEntity1
            ->setUrl('/redirect/source/url/1')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity1->getIdUrlRedirect())
            ->save();

        $urlRedirectEntity2 = new SpyUrlRedirect();
        $urlRedirectEntity2
            ->setToUrl($urlEntity->getUrl())
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity2 = new SpyUrl();
        $urlEntity2
            ->setUrl('/redirect/source/url/2')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity2->getIdUrlRedirect())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $this->urlFacade->deleteUrl($urlTransfer);

        $urlRedirectTransfer1 = new UrlRedirectTransfer();
        $urlRedirectTransfer1->setIdUrlRedirect($urlRedirectEntity1->getIdUrlRedirect());
        $this->assertFalse($this->urlFacade->hasUrlRedirect($urlRedirectTransfer1), 'URL redirect entity 1/2 should not exist after deleting its target url entity.');

        $urlRedirectTransfer2 = new UrlRedirectTransfer();
        $urlRedirectTransfer2->setIdUrlRedirect($urlRedirectEntity2->getIdUrlRedirect());
        $this->assertFalse($this->urlFacade->hasUrlRedirect($urlRedirectTransfer2), 'URL redirect entity 2/2 should not exist after deleting its target url entity.');
    }

    /**
     * @return void
     */
    public function testActivateUrlShouldCreateActiveTouchEntry(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_URL, $urlTransfer->getIdUrl(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);

        $this->assertSame(0, $touchQuery->count(), 'New entity should not have active touch entry before activation.');
        $this->urlFacade->activateUrl($urlTransfer);
        $this->assertSame(1, $touchQuery->count(), 'New entity should have active touch entry after activation.');
    }

    /**
     * @return void
     */
    public function testDeactivateUrlShouldCreateDeletedTouchEntry(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_URL, $urlTransfer->getIdUrl(), SpyTouchTableMap::COL_ITEM_EVENT_DELETED);

        $this->assertSame(0, $touchQuery->count(), 'New entity should not have deleted touch entry before activation.');
        $this->urlFacade->deactivateUrl($urlTransfer);
        $this->assertSame(1, $touchQuery->count(), 'New entity should have deleted touch entry after activation.');
    }

    /**
     * @return void
     */
    public function testCreateUrlRedirectEntityPersistsToDatabase(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $sourceUrlTransfer = new UrlTransfer();
        $sourceUrlTransfer
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale());
        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer
            ->setSource($sourceUrlTransfer)
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(123);

        $urlRedirectTransfer = $this->urlFacade->createUrlRedirect($urlRedirectTransfer);

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_REDIRECT, $urlRedirectTransfer->getIdUrlRedirect(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);

        $this->assertNotNull($urlRedirectTransfer->getIdUrlRedirect(), 'Newly created URL redirect entity should have ID returned.');
        $this->assertSame(1, $touchQuery->count(), 'New entity should have active touch entry after creation.');
    }

    /**
     * @return void
     */
    public function testUpdateUrlRedirectEntityPersistsToDatabase(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $sourceUrlTransfer = new UrlTransfer();
        $sourceUrlTransfer
            ->setIdUrl($urlEntity->getIdUrl())
            ->setUrl('/updated/url/like/string');

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer
            ->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->setToUrl('/updated/url/to/redirect/to')
            ->setSource($sourceUrlTransfer);

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_REDIRECT, $urlRedirectTransfer->getIdUrlRedirect(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);

        $this->assertSame(0, $touchQuery->count(), 'Url redirect entity should not have active touch entry before update.');

        $updatedUrlRedirectTransfer = $this->urlFacade->updateUrlRedirect($urlRedirectTransfer);

        $this->assertSame($urlRedirectTransfer->getToUrl(), $updatedUrlRedirectTransfer->getToUrl(), 'Updated URL redirect entity should have proper data returned.');
        $this->assertSame(1, $touchQuery->count(), 'New entity should have active touch entry after update.');
    }

    /**
     * @return void
     */
    public function testFindUrlRedirectEntityById(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $urlRedirectTransfer = $this->urlFacade->findUrlRedirect($urlRedirectTransfer);

        $this->assertNotNull($urlRedirectTransfer, 'Finding existing URL redirect entity by ID should return transfer object.');
        $this->assertSame($urlRedirectEntity->getToUrl(), $urlRedirectTransfer->getToUrl(), 'Reading URL redirect entity by ID should return transfer with proper data.');
    }

    /**
     * @return void
     */
    public function testHasUrlRedirectEntityById(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $hasUrlRedirect = $this->urlFacade->hasUrlRedirect($urlRedirectTransfer);

        $this->assertTrue($hasUrlRedirect, 'Checking if URL redirect entity exists by ID should return true.');
    }

    /**
     * @return void
     */
    public function testDeleteUrlRedirectShouldRemoveEntityFromDatabaseAlongWithUrlEntity(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $urlRedirectQuery = SpyUrlRedirectQuery::create()->filterByIdUrlRedirect($urlRedirectTransfer->getIdUrlRedirect());
        $urlQuery = SpyUrlQuery::create()->filterByIdUrl($urlEntity->getIdUrl());
        $urlRedirectTouchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(
            UrlConfig::RESOURCE_TYPE_REDIRECT,
            $urlRedirectTransfer->getIdUrlRedirect(),
            SpyTouchTableMap::COL_ITEM_EVENT_DELETED,
        );
        $urlTouchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(
            UrlConfig::RESOURCE_TYPE_URL,
            $urlEntity->getIdUrl(),
            SpyTouchTableMap::COL_ITEM_EVENT_DELETED,
        );

        $this->assertSame(1, $urlRedirectQuery->count(), 'Url redirect entity should exist before deleting it.');
        $this->assertSame(1, $urlQuery->count(), 'Url entity should exist before deleting it.');
        $this->assertSame(0, $urlRedirectTouchQuery->count(), 'URL redirect entity should not have deleted touch entry before deletion.');
        $this->assertSame(0, $urlTouchQuery->count(), 'URL entity should not have deleted touch entry before deletion.');

        $this->urlFacade->deleteUrlRedirect($urlRedirectTransfer);

        $this->assertSame(0, $urlRedirectQuery->count(), 'Url entity should not exist after deleting it.');
        $this->assertSame(0, $urlQuery->count(), 'Url entity should not exist after deleting it.');
        $this->assertSame(1, $urlRedirectTouchQuery->count(), 'URL redirect entity should have deleted touch entry after deletion.');
        $this->assertSame(1, $urlTouchQuery->count(), 'URL entity should have deleted touch entry after deletion.');
    }

    /**
     * @return void
     */
    public function testActivateUrlRedirectShouldCreateActiveTouchEntry(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_REDIRECT, $urlRedirectTransfer->getIdUrlRedirect(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);

        $this->assertSame(0, $touchQuery->count(), 'New entity should not have active touch entry before activation.');
        $this->urlFacade->activateUrlRedirect($urlRedirectTransfer);
        $this->assertSame(1, $touchQuery->count(), 'New entity should have active touch entry after activation.');
    }

    /**
     * @return void
     */
    public function testDeactivateUrlRedirectShouldCreateDeletedTouchEntry(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_REDIRECT, $urlRedirectTransfer->getIdUrlRedirect(), SpyTouchTableMap::COL_ITEM_EVENT_DELETED);

        $this->assertSame(0, $touchQuery->count(), 'New entity should not have deleted touch entry before activation.');
        $this->urlFacade->deactivateUrlRedirect($urlRedirectTransfer);
        $this->assertSame(1, $touchQuery->count(), 'New entity should have deleted touch entry after activation.');
    }

    /**
     * @return void
     */
    public function testValidateUrlRedirectShouldNoticeRedirectLoop(): void
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $sourceUrlTransfer = new UrlTransfer();
        $sourceUrlTransfer->setUrl('/some/url/to/redirect/to');

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer
            ->setSource($sourceUrlTransfer)
            ->setToUrl('/some/url/like/string');

        $urlRedirectValidationResponseTransfer = $this->urlFacade->validateUrlRedirect($urlRedirectTransfer);

        $this->assertFalse($urlRedirectValidationResponseTransfer->getIsValid(), 'URL redirect validation response should notice redirect loops.');
    }
}
