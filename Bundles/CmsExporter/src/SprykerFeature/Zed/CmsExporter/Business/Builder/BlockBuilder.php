<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CmsExporter\Business\Builder;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class BlockBuilder implements BlockBuilderInterface
{

    /**
     * @var KeyBuilderInterface
     */
    protected $blockKeyBuilder;

    /**
     * @param KeyBuilderInterface $blockKeyBuilder
     */
    public function __construct(KeyBuilderInterface $blockKeyBuilder)
    {
        $this->blockKeyBuilder = $blockKeyBuilder;
    }

    /**
     * @param array $blockResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildBlocks(array $blockResultSet, LocaleTransfer $locale)
    {
        $returnedResultSet = [];
        foreach ($blockResultSet as $index => $block) {
            $blockKey = $this->blockKeyBuilder->generateKey($block['block_name'], $locale->getLocaleName());
            $returnedResultSet[$blockKey] = isset($returnedResultSet[$blockKey]) ? $returnedResultSet[$blockKey] : [];
            $returnedResultSet[$blockKey]['name'] = $block['block_name'];
            $returnedResultSet[$blockKey]['id'] = $block['page_id'];
            $returnedResultSet[$blockKey]['template'] = $block['template_path'];
            $returnedResultSet[$blockKey]['placeholders'] = isset($returnedResultSet[$blockKey]['placeholders']) ? $returnedResultSet[$blockKey]['placeholders'] : [];
            $returnedResultSet[$blockKey]['placeholders'][$block['placeholder']] = $block['translation_key'];
        }

        return $returnedResultSet;
    }

}
