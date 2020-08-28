<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockStorage;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class CmsBlockStorageCommunicationTester extends Actor
{
    use _generated\CmsBlockStorageCommunicationTesterActions;

    /**
     * @param int[] $storeIds
     * @param int[] $localeIds
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function createCmsBlock(array $storeIds, array $localeIds): CmsBlockTransfer
    {
        $cmsBlockTransfer = $this->haveCmsBlock([
           CmsBlockTransfer::STORE_RELATION => [
               StoreRelationTransfer::ID_STORES => $storeIds,
           ],
        ]);

        $translations = new ArrayObject();

        foreach ($localeIds as $localeId) {
            $translations->append((new CmsBlockGlossaryPlaceholderTranslationTransfer())
                ->setFkLocale($localeId)
                ->setTranslation('Test translation'));
        }

        $placeholder = new CmsBlockGlossaryPlaceholderTransfer();
        $placeholder->setTranslations($translations);
        $placeholder->setPlaceholder('placeholder');
        $placeholder->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock());
        $placeholder->setTemplateName($cmsBlockTransfer->getTemplateName());

        $glossary = new CmsBlockGlossaryTransfer();
        $glossary->addGlossaryPlaceholder($placeholder);

        $this->getLocator()->cmsBlock()->facade()->saveGlossary($glossary);

        return $cmsBlockTransfer;
    }
}
