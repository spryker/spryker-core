<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Communication\Twig;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Zed\CmsBlock\Communication\Exception\MissingCmsBlockException;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface;
use Twig\Environment;

class RenderCmsBlockAsTwigFunctionProvider extends TwigFunctionProvider
{
    protected const FUNCTION_NAME = 'renderCmsBlockAsTwig';

    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface
     */
    protected $cmsBlockRepository;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface $cmsBlockRepository
     */
    public function __construct(CmsBlockRepositoryInterface $cmsBlockRepository)
    {
        $this->cmsBlockRepository = $cmsBlockRepository;
    }

    /**
     * @param \Twig\Environment $environment
     * @param mixed[] $context
     * @param string $cmsBlockName
     * @param string $storeName
     * @param string $localeName
     * @param mixed[]|null $providedData
     *
     * @throws \Spryker\Zed\CmsBlock\Communication\Exception\MissingCmsBlockException
     *
     * @return string
     */
    public function getCmsBlockTwig(
        Environment $environment,
        array $context,
        string $cmsBlockName,
        string $storeName,
        string $localeName,
        ?array $providedData = null
    ): string {
        $cmsBlockTransfer = $this->cmsBlockRepository
            ->findCmsBlockWithGlossary($cmsBlockName, $storeName, $localeName);

        if (!$cmsBlockTransfer) {
            throw new MissingCmsBlockException(sprintf(
                'Could no find a CMS block for CMS block name "%s", store "%s" and locale "%s"',
                $cmsBlockName,
                $storeName,
                $localeName
            ));
        }

        $templateContext = $providedData ?: $context;

        $placeholdersContent = $this->getPlaceholdersContent(
            $environment,
            $cmsBlockTransfer->getGlossary(),
            $templateContext
        );

        $templateContext['placeholders'] = $placeholdersContent;

        return $environment->render(
            $cmsBlockTransfer->getCmsBlockTemplate()->getTemplatePath(),
            $templateContext
        );
    }

    /**
     * @param \Twig\Environment $environment
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     * @param mixed[] $templateContext
     *
     * @return string[]
     */
    protected function getPlaceholdersContent(
        Environment $environment,
        CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer,
        array $templateContext
    ): array {
        $placeholdersContent = [];

        foreach ($cmsBlockGlossaryTransfer->getGlossaryPlaceholders() as $glossaryPlaceholderTransfer) {
            $placeholderTemplate = $glossaryPlaceholderTransfer->getTranslations()
                ->offsetGet(0)
                ->getTranslation();
            $placeholdersContent[$glossaryPlaceholderTransfer->getPlaceholder()] = $environment
                ->createTemplate($placeholderTemplate)
                ->render($templateContext);
        }

        return $placeholdersContent;
    }

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return static::FUNCTION_NAME;
    }

    /**
     * @return array|callable
     */
    public function getFunction()
    {
        return [$this, 'getCmsBlockTwig'];
    }

    /**
     * @return mixed[]
     */
    public function getOptions()
    {
        $options = parent::getOptions();
        $options['needs_environment'] = true;
        $options['needs_context'] = true;

        return $options;
    }
}
