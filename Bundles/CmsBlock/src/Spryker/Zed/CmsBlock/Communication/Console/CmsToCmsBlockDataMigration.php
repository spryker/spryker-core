<?php

namespace Spryker\Zed\CmsBlock\Communication\Console;

use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\CmsBlock\Communication\CmsBlockCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlock\Business\CmsBlockFacade getFacade()
 */
class CmsToCmsBlockDataMigration extends Console
{
    const COMMAND_NAME = 'cms-cms-block:migrate';
    const COMMAND_DESCRIPTION = 'Migrates CMS Block data from CMS module';

    const COMMAND_ARGUMENT_DRY_RUN = 'dry-run';

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $spyCmsBlocks = SpyCmsBlockQuery::create()
            ->filterByFkPage(null, Criteria::NOT_EQUAL)
            ->filterByFkTemplate(null, Criteria::EQUAL)
            ->joinWith('SpyPage')
            ->find();

        $spyCmsBlocksCount = count($spyCmsBlocks);

        $output->writeln(sprintf('Processing %s blocks...', $spyCmsBlocksCount));

        foreach ($spyCmsBlocks as $spyCmsBlock) {
            $spyCmsPage = SpyCmsPageQuery::create()
                ->filterByIdCmsPage($spyCmsBlock->getFkPage())
                ->findOne();

            //template
            $spyCmsTemplate = $spyCmsPage->getCmsTemplate();
            $spyCmsBlockTemplate = new SpyCmsBlockTemplate();
            $spyCmsBlockTemplate->setTemplateName($spyCmsTemplate->getTemplateName());
            $spyCmsBlockTemplate->setTemplatePath($spyCmsTemplate->getTemplatePath());
            $spyCmsBlockTemplate->save();

            //validity
            $spyCmsBlock->setValidFrom($spyCmsPage->getValidFrom());
            $spyCmsBlock->setValidTo($spyCmsPage->getValidTo());

            //is active
            $spyCmsBlock->setIsActive($spyCmsPage->getIsActive());

            //category migration will be in CmsBlockCategoryConnector
//            if ($spyCmsBlock->getType() === 'category') {
//                $spyCmsBlockRelation = new Smth();
//                $spyCmsBlockRelation->setIdCategory
//            }

            $spyCmsBlock->save();
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
        $this->setDescription(static::COMMAND_DESCRIPTION);

        $this->addOption(
            static::COMMAND_ARGUMENT_DRY_RUN,
            null,
            InputOption::VALUE_REQUIRED,
            'Run without database changes'
        );
    }

}