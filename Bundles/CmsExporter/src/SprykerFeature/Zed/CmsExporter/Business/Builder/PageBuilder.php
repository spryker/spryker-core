<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CmsExporter\Business\Builder;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class PageBuilder implements PageBuilderInterface
{

    /**
     * @var KeyBuilderInterface
     */
    protected $pageKeyBuilder;

    /**
     * @param KeyBuilderInterface $pageKeyBuilder
     */
    public function __construct(KeyBuilderInterface $pageKeyBuilder)
    {
        $this->pageKeyBuilder = $pageKeyBuilder;
    }

    /**
     * @param array $pageResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildPages(array $pageResultSet, LocaleTransfer $locale)
    {
        $returnedResultSet = [];
        foreach ($pageResultSet as $index => $page) {
            $pageKey = $this->pageKeyBuilder->generateKey($page['page_id'], $locale->getLocaleName());
            $returnedResultSet[$pageKey] = isset($returnedResultSet[$pageKey]) ? $returnedResultSet[$pageKey] : [];
            $returnedResultSet[$pageKey]['url'] = $page['page_url'];
            $returnedResultSet[$pageKey]['id'] = $page['page_id'];
            $returnedResultSet[$pageKey]['template'] = $page['template_path'];
            $returnedResultSet[$pageKey]['placeholders'] = isset($returnedResultSet[$pageKey]['placeholders']) ? $returnedResultSet[$pageKey]['placeholders'] : [];
            $returnedResultSet[$pageKey]['placeholders'][$page['placeholder']] = $page['translation_key'];
        }

        return $returnedResultSet;
    }

}
