<?php

namespace Spryker\Zed\Category\Communication\Console;


use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Category\Persistence\SpyCategoryTemplate;
use Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery;
use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CategoryTemplateImporter extends Console
{
    const COMMAND_NAME = 'category-template:import';

    protected $output;


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        if ($this->isCategoryTemplateIntalled()) {
            $output->writeln('Is already installed.');
            return;
        }

        foreach ($this->getTemplateList() as $templateName => $templatePath) {

            $spyCategoryTemplate = SpyCategoryTemplateQuery::create()
                ->filterByName($templateName)
                ->findOne();

            if (!$spyCategoryTemplate) {
                $spyCategoryTemplate = new SpyCategoryTemplate();
                $spyCategoryTemplate->setName($templateName);
                $spyCategoryTemplate->setTemplatePath($templatePath);
                $spyCategoryTemplate->save();

                $output->writeln('Template ['. $templateName .'] is created.');
            } else {
                $output->writeln('Template ['. $templateName .'] exists.');
            }

        }

        $this->assignTemplateToAllCategories();

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
     * @return void
     */
    protected function assignTemplateToAllCategories()
    {
        $spyCategoryTemplate = SpyCategoryTemplateQuery::create()
            ->filterByName(CategoryConfig::CATEGORY_TEMPLATE_DEFAULT)
            ->findOne();

        $query = SpyCategoryQuery::create()
            ->filterByFkCategoryTemplate(null, Criteria::ISNULL);

        $this->output->writeln('Will update ' . $query->count() . ' categories without template.');

        foreach ($query->find() as $category) {
            $category->setFkCategoryTemplate($spyCategoryTemplate->getIdCategoryTemplate());
            $category->save();
        }
    }

    /**
     * @return array
     */
    protected function getTemplateList()
    {
        return $this->getFactory()
            ->getConfig()
            ->getTemplateList();
    }

    /**
     * @return bool
     */
    protected function isCategoryTemplateIntalled()
    {
        $count = SpyCategoryTemplateQuery::create()
            ->filterByName_In($this->getTemplateList())
            ->count();

        return $count >= count($this->getTemplateList());
    }
}