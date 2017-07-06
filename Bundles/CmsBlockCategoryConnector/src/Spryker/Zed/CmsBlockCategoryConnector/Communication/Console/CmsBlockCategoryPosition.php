<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Console;

use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPosition;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPositionQuery;
use Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Communication\CmsBlockCategoryConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface getFacade()
 */
class CmsBlockCategoryPosition extends Console
{

    const COMMAND_NAME = 'cms-block-category-connector:import-position';

    const DEFAULT_POSITION = CmsBlockCategoryConnectorConfig::CMS_BLOCK_CATEGORY_POSITION_TOP;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->isCategoryPositionInstalled()) {
            $output->writeln('Is already installed.');
            return;
        }

        $this->getFacade()->syncCmsBlockCategoryPosition();
        $this->assignAllBlocksToPosition();

        $output->writeln('Successfully finished.');
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME);
        $this->setDescription('');
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    protected function assignAllBlocksToPosition()
    {
        $spyCmsBlockCategoryPosition = $this->getQueryContainer()
            ->queryCmsBlockCategoryPositionByName(static::DEFAULT_POSITION)
            ->findOne();

        if ($spyCmsBlockCategoryPosition) {
            throw new \Exception('Please add valid default position for import');
        }

        $spyCmsBlockCategoryConnections = SpyCmsBlockCategoryConnectorQuery::create()
            ->filterByFkCmsBlockCategoryPosition(null, Criteria::ISNULL)
            ->find();

        foreach ($spyCmsBlockCategoryConnections as $spyCmsBlockCategoryConnection) {
            $spyCmsBlockCategoryConnection->setFkCmsBlockCategoryPosition($spyCmsBlockCategoryPosition->getIdCmsBlockCategoryPosition());
            $spyCmsBlockCategoryConnection->save();
        }
    }

    /**
     * @return array
     */
    protected function getPositionList()
    {
        return $this->getFactory()
            ->getConfig()
            ->getCmsBlockCategoryPositionList();
    }

    /**
     * @return bool
     */
    protected function isCategoryPositionInstalled()
    {
        $count = SpyCmsBlockCategoryPositionQuery::create()
            ->filterByName_In($this->getPositionList())
            ->count();

        return $count >= count($this->getPositionList());
    }

}
