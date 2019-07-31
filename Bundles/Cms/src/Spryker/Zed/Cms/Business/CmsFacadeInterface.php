<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;
use Generated\Shared\Transfer\LocaleCmsPageDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;

interface CmsFacadeInterface
{
    /**
     * @api
     *
     * @param string $name
     * @param string $path
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\TemplateExistsException
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function createTemplate(string $name, string $path): CmsTemplateTransfer;

    /**
     * @api
     *
     * @param string $path
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingTemplateException
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function getTemplate(string $path): CmsTemplateTransfer;

    /**
     * @api
     *
     * @param string $path
     *
     * @return bool
     */
    public function hasTemplate(string $path): bool;

    /**
     * @api
     *
     * @deprecated Use `\Spryker\Zed\Cms\Business\CmsFacade::createPage()` or `\Spryker\Zed\Cms\Business\CmsFacade::updatePage()` instead.
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Generated\Shared\Transfer\PageTransfer
     */
    public function savePage(PageTransfer $pageTransfer): PageTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageKeyMappingTransfer $pageKeyMappingTransfer
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function savePageKeyMapping(PageKeyMappingTransfer $pageKeyMappingTransfer): PageKeyMappingTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageKeyMappingTransfer $pageKeyMappingTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function savePageKeyMappingAndTouch(PageKeyMappingTransfer $pageKeyMappingTransfer, ?LocaleTransfer $localeTransfer = null): PageKeyMappingTransfer;

    /**
     * @api
     *
     * @param int $idPage
     * @param string $placeholder
     *
     * @return bool
     */
    public function hasPagePlaceholderMapping(int $idPage, string $placeholder): bool;

    /**
     * @api
     *
     * @param int $idPage
     * @param string $placeholder
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function getPagePlaceholderMapping(int $idPage, string $placeholder): PageKeyMappingTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsTemplateTransfer $cmsTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function saveTemplate(CmsTemplateTransfer $cmsTemplateTransfer): CmsTemplateTransfer;

    /**
     * @api
     *
     * @param int $idPage
     * @param string $placeholder
     * @param array $data
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return string
     */
    public function translatePlaceholder(int $idPage, string $placeholder, array $data = []): string;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $placeholder
     * @param string $value
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     * @param bool $autoGlossaryKeyIncrement
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function addPlaceholderText(PageTransfer $pageTransfer, string $placeholder, string $value, ?LocaleTransfer $localeTransfer = null, bool $autoGlossaryKeyIncrement = true): PageKeyMappingTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $placeholder
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function deletePageKeyMapping(PageTransfer $pageTransfer, string $placeholder): bool;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function touchPageActive(PageTransfer $pageTransfer, ?LocaleTransfer $localeTransfer = null): void;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function savePageUrlAndTouch(PageTransfer $pageTransfer): UrlTransfer;

    /**
     * @api
     *
     * @param int $idPage
     *
     * @return bool
     */
    public function deleteGlossaryKeysByIdPage(int $idPage): bool;

    /**
     * @api
     *
     * @param string $cmsTemplateFolderPath
     *
     * @return bool
     */
    public function syncTemplate(string $cmsTemplateFolderPath): bool;

    /**
     * @api
     *
     * @param string $templateName
     * @param string $placeholder
     *
     * @return string
     */
    public function generateGlossaryKeyName(string $templateName, string $placeholder): string;

    /**
     * Specification:
     * - Deletes Cms Page and its relations (urls, glossary key mappings) from database
     * - Touches deleted Cms Page to notify collector about the change
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return void
     */
    public function deletePageById(int $idCmsPage): void;

    /**
     * Specification:
     * - Reads cms page placeholders with translations.
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer|null
     */
    public function findPageGlossaryAttributes(int $idCmsPage): ?CmsGlossaryTransfer;

    /**
     * Specification:
     * - Saves cms glossary placeholders
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function saveCmsGlossary(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer;

    /**
     * Specification:
     * - Creates new Cms page
     * - Touches cms collector
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return int
     */
    public function createPage(CmsPageTransfer $cmsPageTransfer): int;

    /**
     * Specification:
     * - Creates new CMS page with given URL and Locale
     * - Touches cms collector
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrlWithLocale(PageTransfer $pageTransfer, string $url, LocaleTransfer $localeTransfer): UrlTransfer;

    /**
     * Specification:
     * - Reads cms page by given id
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer|null
     */
    public function findCmsPageById(int $idCmsPage): ?CmsPageTransfer;

    /**
     * Specification:
     * - Updates existing cms page with new data
     * - Touches cms collector
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function updatePage(CmsPageTransfer $cmsPageTransfer): CmsPageTransfer;

    /**
     * Specification:
     * - Activates page, set active flat to 1 in database
     * - Touches cms collector
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return void
     */
    public function activatePage(int $idCmsPage): void;

    /**
     * Specification:
     * - Deactivates page, set active flat to 0 in database
     * - Touches cms collector
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return void
     */
    public function deactivatePage(int $idCmsPage): void;

    /**
     * Specification:
     * - Creates prefix to be appended in front of url
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function getPageUrlPrefix(CmsPageAttributesTransfer $cmsPageAttributesTransfer): string;

    /**
     * Specification:
     * - Creates page url for persistence, from give localized data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function buildPageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer): string;

    /**
     * Specification:
     * - Creates a cms version for page.
     * - Creates a generated version name if $versionName is null.
     * - Touches cms page with given idCmsPage.
     * - Executes PostSavePlugins
     *
     * @api
     *
     * @param int $idCmsPage
     * @param string|null $versionName
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function publishWithVersion(int $idCmsPage, ?string $versionName = null): CmsVersionTransfer;

    /**
     * Specification:
     * - Retrieves current CMS version data with attributes from permanent storage.
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function getCmsVersionData(int $idCmsPage): CmsVersionDataTransfer;

    /**
     * Specification:
     * - Populates CmsVersionData transfer object from provided CMS page JSON data.
     *
     * @api
     *
     * @param string $cmsPageData
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function extractCmsVersionDataTransfer(string $cmsPageData): CmsVersionDataTransfer;

    /**
     * Specification:
     * - Populates LocaleCmsPageData transfer object using provided CmsVersionData transfer object for the specified locale.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $cmsVersionDataTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleCmsPageDataTransfer
     */
    public function extractLocaleCmsPageDataTransfer(CmsVersionDataTransfer $cmsVersionDataTransfer, LocaleTransfer $localeTransfer): LocaleCmsPageDataTransfer;

    /**
     * Specification:
     * - Flattens provided LocaleCmsPageData transfer object.
     * - Expands flattened data with pre-configured CmsPageDataExpanderPluginInterface plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleCmsPageDataTransfer $localeCmsPageDataTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function calculateFlattenedLocaleCmsPageData(LocaleCmsPageDataTransfer $localeCmsPageDataTransfer, LocaleTransfer $localeTransfer): array;

    /**
     * Specification:
     * - Rollbacks latest CmsPageVersion to older version.
     * - Creates a reference cms version copy
     * - Calls publishWithVersion() method
     *
     * @api
     *
     * @param int $idCmsPage
     * @param int $version
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function rollback(int $idCmsPage, int $version): CmsVersionTransfer;

    /**
     * Specification:
     * - Revert all cms changes to the latest CmsPageVersion
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return void
     */
    public function revert(int $idCmsPage): void;

    /**
     * Specification:
     * - Returns the latest CmsPageVersionTransfer by IdCmsPage
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer|null
     */
    public function findLatestCmsVersionByIdCmsPage(int $idCmsPage): ?CmsVersionTransfer;

    /**
     * Specification:
     * - Returns All CmsPageVersions by IdCmsPage
     *
     * @api
     *
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer[]
     */
    public function findAllCmsVersionByIdCmsPage(int $idCmsPage): array;

    /**
     * Specification:
     * - Returns the CmsPageVersionTransfer by IdCmsPage and specific version
     *
     * @api
     *
     * @param int $idCmsPage
     * @param int $version
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer|null
     */
    public function findCmsVersionByIdCmsPageAndVersion(int $idCmsPage, int $version): ?CmsVersionTransfer;
}
