<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotGui;

use Codeception\Actor;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotToCmsSlotTemplateQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class CmsSlotGuiCommunicationTester extends Actor
{
    use _generated\CmsSlotGuiCommunicationTesterActions;

    /**
     * @param int $idCmsSlot
     * @param int $idCmsSlotTemplate
     *
     * @return void
     */
    public function haveCmsSlotCmsToSlotTemplateConnection(int $idCmsSlot, int $idCmsSlotTemplate): void
    {
        $cmsSlotToCmsSlotTemplateEntity = SpyCmsSlotToCmsSlotTemplateQuery::create()
            ->filterByFkCmsSlot($idCmsSlot)
            ->filterByFkCmsSlotTemplate($idCmsSlotTemplate)
            ->findOneOrCreate();

        if ($cmsSlotToCmsSlotTemplateEntity->isNew()) {
            $cmsSlotToCmsSlotTemplateEntity->save();
        }

        $this->addCleanup(function () use ($cmsSlotToCmsSlotTemplateEntity): void {
            $this->deleteCmsSlotToCmsSlotTemplateConnection($cmsSlotToCmsSlotTemplateEntity->getIdCmsSlotToCmsSlotTemplate());
        });
    }

    /**
     * @param int $idCmsSlotToCmsSlotTemplate
     *
     * @return void
     */
    protected function deleteCmsSlotToCmsSlotTemplateConnection(int $idCmsSlotToCmsSlotTemplate): void
    {
        SpyCmsSlotToCmsSlotTemplateQuery::create()
            ->filterByIdCmsSlotToCmsSlotTemplate($idCmsSlotToCmsSlotTemplate)
            ->delete();
    }
}
