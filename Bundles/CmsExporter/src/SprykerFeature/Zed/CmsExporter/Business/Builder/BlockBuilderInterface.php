<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CmsExporter\Business\Builder;

use Generated\Shared\Transfer\LocaleTransfer;

interface BlockBuilderInterface
{

    /**
     * @param array $blockResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildBlocks(array $blockResultSet, LocaleTransfer $locale);

}
