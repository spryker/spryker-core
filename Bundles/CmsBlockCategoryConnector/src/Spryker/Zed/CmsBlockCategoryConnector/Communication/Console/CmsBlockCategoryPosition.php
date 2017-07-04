<?php

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
 */
class CmsBlockCategoryPosition extends Console
{
    const COMMAND_NAME = 'cms-block-category-connector:import-position';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->isCategoryPositionInstalled()) {
            $output->writeln('Is already installed.');
            return;
        }

        foreach ($this->getPositionList() as $positionName) {
            $spyCmsBlockCategoryPosition = SpyCmsBlockCategoryPositionQuery::create()
                ->filterByName($positionName)
                ->findOne();

            if (!$spyCmsBlockCategoryPosition) {
                $spyCmsBlockCategoryPosition = new SpyCmsBlockCategoryPosition();
                $spyCmsBlockCategoryPosition->setName($positionName);
                $spyCmsBlockCategoryPosition->save();
                $output->writeln('Position [' . $positionName . '] is imported');
            } else {
                $output->writeln('Position [' . $positionName . '] exists');
            }

            $this->assignAllBlocksToPosition($spyCmsBlockCategoryPosition);
        }

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
     * @param SpyCmsBlockCategoryPosition $spyCmsBlockCategoryPosition
     */
    protected function assignAllBlocksToPosition(SpyCmsBlockCategoryPosition $spyCmsBlockCategoryPosition)
    {
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