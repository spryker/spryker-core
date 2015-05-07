<?php

namespace SprykerFeature\Zed\Cms\Business\Mapping;

use Propel\Runtime\Exception\PropelException;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsPageKeyMappingTransfer;
use SprykerFeature\Zed\Cms\Business\Exception\MappingAmbiguousException;
use SprykerFeature\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingTranslationException;

interface GlossaryKeyMappingManagerInterface
{
    /**
     * @param int $idPage
     * @param string $placeholder
     * @param array $data
     *
     * @return string
     * @throws MissingGlossaryKeyMappingException
     * @throws MissingTranslationException
     */
    public function translatePlaceholder($idPage, $placeholder, array $data = []);

    /**
     * @param CmsPageKeyMappingTransfer $pageKeyMapping
     *
     * @return CmsPageKeyMappingTransfer
     * @throws MappingAmbiguousException
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     */
    public function savePageKeyMapping(CmsPageKeyMappingTransfer $pageKeyMapping);

    /**
     * @param CmsPageTransfer $page
     * @param string $placeholder
     * @param string $value
     *
     * @return CmsPageKeyMappingTransfer
     */
    public function addPlaceholderText(CmsPageTransfer $page, $placeholder, $value);

    /**
     * @param CmsPageTransfer $page
     * @param string $placeholder
     *
     * @return bool
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     */
    public function deletePageKeyMapping(CmsPageTransfer $page, $placeholder);

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return CmsPageKeyMappingTransfer
     * @throws MissingGlossaryKeyMappingException
     */
    public function getPagePlaceholderMapping($idPage, $placeholder);

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return bool
     */
    public function hasPagePlaceholderMapping($idPage, $placeholder);
}
