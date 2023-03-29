<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraphDumper;

use Generated\Shared\Transfer\ProfilerDataTransfer;
use Spryker\Shared\Profiler\Exception\ProfilerCommandFailedException;
use Spryker\Shared\Profiler\Exception\ProfilerRootNodeNotFoundException;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphInterface;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface;
use Symfony\Component\Process\Process;

class SvgProfilerGraphDumper implements ProfilerGraphDumperInterface
{
    /**
     * @var string
     */
    protected const SVG_DATA_TYPE = 'svg';

    /**
     * @var string
     */
    protected const STATS_MODULES_CALLS = 'Calls';

    /**
     * @var array<\Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface>
     */
    protected array $processedNodes = [];

    /**
     * @var iterable<\Spryker\Shared\Profiler\ProfilerGraphDumper\ProfilerGraphNodeStylerInterface>
     */
    protected iterable $profilerNodeStylerCommands;

    /**
     * @param iterable<\Spryker\Shared\Profiler\ProfilerGraphDumper\ProfilerGraphNodeStylerInterface> $profilerNodeStylerCommands
     */
    public function __construct(iterable $profilerNodeStylerCommands)
    {
        $this->profilerNodeStylerCommands = $profilerNodeStylerCommands;
    }

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphInterface $profilerGraph
     *
     * @throws \Spryker\Shared\Profiler\Exception\ProfilerRootNodeNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProfilerDataTransfer
     */
    public function dump(ProfilerGraphInterface $profilerGraph): ProfilerDataTransfer
    {
        $this->processedNodes = [];

        $rootNode = $profilerGraph->findNodeByName(ProfilerGraphInterface::ROOT_NODE_NAME);

        if ($rootNode === null) {
            throw new ProfilerRootNodeNotFoundException(sprintf('Profiler Graph node "%s" is not found', ProfilerGraphInterface::ROOT_NODE_NAME));
        }

        $command = $this->buildGraphvizDotCommandString($rootNode);

        $svgData = $this->executeSvgDrawingCommand($command);

        $svgData = $this->filterSvgData($svgData);

        return $this->createProfilerDataTransfer($svgData);
    }

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $node
     *
     * @return string
     */
    protected function buildGraphvizDotCommandString(ProfilerGraphNodeInterface $node): string
    {
        $command = 'digraph call_graph {';

        $nodeCommandItems = $this->processNodes($node);

        $command .= implode(' ', [...$this->addNodesStyles(), ...$nodeCommandItems]);

        $command .= '}';

        return str_replace('\\', '\\\\', $command);
    }

    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $node
     *
     * @return array<string>
     */
    protected function processNodes(ProfilerGraphNodeInterface $node): array
    {
        if (in_array($node, $this->processedNodes, true)) {
            return [];
        }

        $this->processedNodes[] = $node;
        $processedNodeCommands = [[]];

        foreach ($node->getToNodes() as $toNode) {
            $processedNodeCommands[] = [sprintf('"%s" -> "%s";', $node->getName(), $toNode->getName())];
            $processedNodeCommands[] = $this->processNodes($toNode);
        }

        return array_merge(...$processedNodeCommands);
    }

    /**
     * @param string $command
     *
     * @throws \Spryker\Shared\Profiler\Exception\ProfilerCommandFailedException
     *
     * @return string
     */
    protected function executeSvgDrawingCommand(string $command): string
    {
        $process = new Process(['dot', '-Tsvg'], sys_get_temp_dir());
        $process->setInput($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProfilerCommandFailedException($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    /**
     * @param string $svgData
     *
     * @return string
     */
    protected function filterSvgData(string $svgData): string
    {
        $matches = [];

        if (preg_match('/<svg.*>.*<\\/svg>/s', $svgData, $matches)) {
            $svgData = $matches[0];
        }

        return $svgData;
    }

    /**
     * @param string $svgData
     *
     * @return \Generated\Shared\Transfer\ProfilerDataTransfer
     */
    protected function createProfilerDataTransfer(string $svgData): ProfilerDataTransfer
    {
        $profilerDataStats = [static::STATS_MODULES_CALLS => count($this->processedNodes)];

        return (new ProfilerDataTransfer())
            ->setContent($svgData)
            ->setStats($profilerDataStats)
            ->setType(static::SVG_DATA_TYPE);
    }

    /**
     * @return array<string>
     */
    protected function addNodesStyles(): array
    {
        $nodesStyles = [];

        foreach ($this->processedNodes as $node) {
            foreach ($this->profilerNodeStylerCommands as $profilerNodeStylerCommand) {
                $nodesStyles[] = $profilerNodeStylerCommand->apply($node);
            }
        }

        return $nodesStyles;
    }
}
