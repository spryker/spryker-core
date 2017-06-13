<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplatePathExistsException;
use Spryker\Zed\CmsBlock\CmsBlockConfig;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;
use Symfony\Component\Finder\Finder;

class CmsBlockTemplateManager implements CmsBlockTemplateManagerInterface
{

    const TEMPLATE_FILE_SUFFIX = '.twig';

    /**
     * @var CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var CmsBlockTemplateMapperInterface
     */
    protected $cmsBlockTemplateMapper;

    /**
     * @var \Spryker\Zed\CmsBlock\CmsBlockConfig
     */
    protected $config;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * CmsBlockTemplateManager constructor.
     * @param CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param CmsBlockTemplateMapperInterface $cmsBlockTemplateMapper
     * @param CmsBlockConfig $cmsBlockConfig
     * @param Finder $finder
     */
    public function __construct(
        CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockTemplateMapperInterface $cmsBlockTemplateMapper,
        CmsBlockConfig $cmsBlockConfig,
        Finder $finder
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->cmsBlockTemplateMapper = $cmsBlockTemplateMapper;
        $this->config = $cmsBlockConfig;
        $this->finder = $finder;
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @return CmsBlockTemplateTransfer
     */
    public function createTemplate($name, $path)
    {
        $this->checkTemplatePathDoesNotExist($path);

        $template = new SpyCmsBlockTemplate();
        $template
            ->setTemplateName($name)
            ->setTemplatePath($path)
            ->save();

        return $this->cmsBlockTemplateMapper->convertTemplateEntityToTransfer($template);
    }

    /**
     * @param string $templatePath
     *
     * @return bool
     */
    public function syncTemplate($templatePath)
    {
        $templateFolders = $this->config->getTemplateRealPaths($templatePath);
        $isSynced = false;

        foreach ($templateFolders as $folder) {
            if (!file_exists($folder)) {
                continue;
            }

            $isSynced = $this->findTwigFileAndCreateTemplate($templatePath, $folder);
        }

        return $isSynced;
    }

    /**
     * @param string $path
     *
     * @throws CmsBlockTemplateNotFoundException
     *
     * @return void
     */
    public function checkTemplateFileExists($path)
    {
        if (!$this->isTemplateFileExists($path)) {
            throw new CmsBlockTemplateNotFoundException(
                sprintf('Template file not found in "%s"', $path)
            );
        }
    }

    /**
     * @param string $path
     *
     * @throws CmsBlockTemplatePathExistsException
     *
     * @return void
     */
    protected function checkTemplatePathDoesNotExist($path)
    {
        if ($this->hasTemplatePath($path)) {
            throw new CmsBlockTemplatePathExistsException(
                sprintf(
                    'Tried to create a template with path %s, but it already exists',
                    $path
                )
            );
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function hasTemplatePath($path)
    {
        return $this->cmsBlockQueryContainer
            ->queryTemplateByPath($path)
            ->exists();
    }

    /**
     * @param string $realPath
     *
     * @return bool
     */
    protected function isTemplateFileExists($realPath)
    {
        $realPaths = $this->config->getTemplateRealPaths($realPath);

        foreach ($realPaths as $realPath) {
            if (file_exists($realPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $cmsTemplateFolderPath
     * @param string $folder
     *
     * @return bool
     */
    protected function findTwigFileAndCreateTemplate($cmsTemplateFolderPath, $folder)
    {
        $isTemplateCreated = false;
        $this->finder->in($folder)
            ->name('*' . static::TEMPLATE_FILE_SUFFIX)
            ->depth('0');

        foreach ($this->finder->files() as $file) {
            $fullFileName = $file->getRelativePathname();
            $exists = $this->cmsBlockQueryContainer->queryTemplateByPath($cmsTemplateFolderPath . $fullFileName)
                ->exists();

            if (!$exists) {
                $fileName = basename($folder . $fullFileName, static::TEMPLATE_FILE_SUFFIX);
                $this->createTemplate($fileName, $cmsTemplateFolderPath . $fullFileName);
                $isTemplateCreated = true;
            }
        }

        return $isTemplateCreated;
    }

}