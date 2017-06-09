<?php

namespace Spryker\Zed\CmsBlock\Communication\Console;

use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplate;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
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
            ->filterByFkPage(null, Criteria::ISNOTNULL)
            ->filterByFkTemplate(null, Criteria::ISNULL)
            ->find();

        $spyCmsBlocksCount = count($spyCmsBlocks);

        $output->writeln(sprintf('Processing %s blocks...', $spyCmsBlocksCount));

        foreach ($spyCmsBlocks as $spyCmsBlock) {
            $spyCmsPage = SpyCmsPageQuery::create()
                ->filterByIdCmsPage($spyCmsBlock->getFkPage())
                ->findOne();

            $spyCmsTemplate = $spyCmsPage->getCmsTemplate();

            //template
            $spyCmsBlockTemplate = $this->createCmsBlockTemplate($spyCmsTemplate);
            $spyCmsBlock->setFkTemplate($spyCmsBlockTemplate->getIdCmsBlockTemplate());

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
     * @param SpyCmsTemplate $spyCmsTemplate
     *
     * @return SpyCmsBlockTemplate
     */
    protected function createCmsBlockTemplate(SpyCmsTemplate $spyCmsTemplate)
    {
        $spyCmsBlockTemplate = SpyCmsBlockTemplateQuery::create()
            ->filterByTemplatePath($spyCmsTemplate->getTemplatePath())
            ->findOne();

        if (empty($spyCmsBlockTemplate)) {
            $spyCmsBlockTemplate = new SpyCmsBlockTemplate();
            $spyCmsBlockTemplate->setTemplateName($spyCmsTemplate->getTemplateName());
            $spyCmsBlockTemplate->setTemplatePath($spyCmsTemplate->getTemplatePath());
            $spyCmsBlockTemplate->save();
        }

        return $spyCmsBlockTemplate;
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