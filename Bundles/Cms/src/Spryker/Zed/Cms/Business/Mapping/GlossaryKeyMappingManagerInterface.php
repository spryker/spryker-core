<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Business\Mapping;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Cms\Business\Exception\MappingAmbiguousException;
use Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException;
use Spryker\Zed\Glossary\Business\Exception\MissingTranslationException;

interface GlossaryKeyMappingManagerInterface
{

    /**
     * @param int $idPage
     * @param string $placeholder
     * @param array $data
     *
     * @throws MissingGlossaryKeyMappingException
     * @throws MissingTranslationException
     *
     * @return string
     */
    public function translatePlaceholder($idPage, $placeholder, array $data = []);

    /**
     * @param PageKeyMappingTransfer $pageKeyMapping
     *
     * @throws MappingAmbiguousException
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     *
     * @return PageKeyMappingTransfer
     */
    public function savePageKeyMapping(PageKeyMappingTransfer $pageKeyMapping);

    /**
     * @param PageKeyMappingTransfer $pageKeyMapping
     *
     * @return PageKeyMappingTransfer
     */
    public function savePageKeyMappingAndTouch(PageKeyMappingTransfer $pageKeyMapping);

    /**
     * @param PageTransfer $page
     * @param string $placeholder
     * @param string $value
     * @param LocaleTransfer $localeTransfer
     * @param bool $autoGlossaryKeyIncrement
     *
     * @return PageKeyMappingTransfer
     */
    public function addPlaceholderText(PageTransfer $page, $placeholder, $value, LocaleTransfer $localeTransfer = null, $autoGlossaryKeyIncrement = true);

    /**
     * @param PageTransfer $page
     * @param string $placeholder
     *
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     *
     * @return bool
     */
    public function deletePageKeyMapping(PageTransfer $page, $placeholder);

    /**
     * @param int $idPage
     *
     * @return bool
     */
    public function deleteGlossaryKeysByIdPage($idPage);

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @throws MissingGlossaryKeyMappingException
     *
     * @return PageKeyMappingTransfer
     */
    public function getPagePlaceholderMapping($idPage, $placeholder);

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return bool
     */
    public function hasPagePlaceholderMapping($idPage, $placeholder);

    /**
     * @param string $templateName
     * @param string $placeholder
     * @param bool $autoIncrement
     *
     * @return string
     */
    public function generateGlossaryKeyName($templateName, $placeholder, $autoIncrement = true);

}
