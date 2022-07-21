<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Mapping;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;

interface GlossaryKeyMappingManagerInterface
{
    /**
     * @param int $idPage
     * @param string $placeholder
     * @param array<string, mixed> $data
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return string
     */
    public function translatePlaceholder(int $idPage, string $placeholder, array $data = []): string;

    /**
     * @param \Generated\Shared\Transfer\PageKeyMappingTransfer $pageKeyMappingTransfer
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MappingAmbiguousException
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function savePageKeyMapping(PageKeyMappingTransfer $pageKeyMappingTransfer): PageKeyMappingTransfer;

    /**
     * @param \Generated\Shared\Transfer\PageKeyMappingTransfer $pageKeyMappingTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function savePageKeyMappingAndTouch(PageKeyMappingTransfer $pageKeyMappingTransfer, ?LocaleTransfer $localeTransfer = null): PageKeyMappingTransfer;

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $placeholder
     * @param string $value
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     * @param bool $autoGlossaryKeyIncrement
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function addPlaceholderText(
        PageTransfer $pageTransfer,
        string $placeholder,
        string $value,
        ?LocaleTransfer $localeTransfer = null,
        bool $autoGlossaryKeyIncrement = true
    ): PageKeyMappingTransfer;

    /**
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
     * @param int $idPage
     *
     * @return bool
     */
    public function deleteGlossaryKeysByIdPage(int $idPage): bool;

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function getPagePlaceholderMapping(int $idPage, string $placeholder): PageKeyMappingTransfer;

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return bool
     */
    public function hasPagePlaceholderMapping(int $idPage, string $placeholder): bool;

    /**
     * @param string $templateName
     * @param string $placeholder
     * @param bool $autoIncrement
     *
     * @return string
     */
    public function generateGlossaryKeyName(string $templateName, string $placeholder, bool $autoIncrement = true): string;
}
