<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Business\Mapping;

use Propel\Runtime\Exception\PropelException;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
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
     * @param PageKeyMappingTransfer $pageKeyMapping
     *
     * @return PageKeyMappingTransfer
     * @throws MappingAmbiguousException
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     */
    public function savePageKeyMapping(PageKeyMappingTransfer $pageKeyMapping);

    /**
     * @param PageTransfer $page
     * @param string $placeholder
     * @param string $value
     *
     * @return PageKeyMappingTransfer
     */
    public function addPlaceholderText(PageTransfer $page, $placeholder, $value);

    /**
     * @param PageTransfer $page
     * @param string $placeholder
     *
     * @return bool
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     */
    public function deletePageKeyMapping(PageTransfer $page, $placeholder);

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return PageKeyMappingTransfer
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
