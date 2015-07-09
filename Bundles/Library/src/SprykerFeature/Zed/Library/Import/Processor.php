<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import;

use Psr\Log\LoggerInterface;

/**
 * Default implementation for a processor
 */
class Processor implements ProcessorInterface
{

    /**
     * @var ReaderInterface
     */
    protected $reader;

    /**
     * @var TypeDetectorInterface
     */
    protected $typeDetector;

    /**
     * @var ProcessInterface[]
     */
    protected $processes;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $statusMessage = 'no started';

    /**
     * @var string
     */
    private $deleteNullValues;

    /**
     * @param ReaderInterface $reader
     * @param TypeDetectorInterface $typeDetector
     * @param ProcessInterface[] $processList
     * @param LoggerInterface $logger
     */
    public function __construct(ReaderInterface $reader, TypeDetectorInterface $typeDetector, array $processList = [], LoggerInterface $logger = null)
    {
        $this->reader = $reader;
        $this->typeDetector = $typeDetector;
        $this->setProcesses($processList);
        $this->logger = $logger;
    }

    /**
     * @param ReaderInterface $reader
     *
     * @return $this
     */
    public function setReader(ReaderInterface $reader)
    {
        $this->reader = $reader;

        return $this;
    }

    /**
     * @param TypeDetectorInterface $typeDetector
     *
     * @return $this
     */
    public function setTypeDetector(TypeDetectorInterface $typeDetector)
    {
        $this->typeDetector = $typeDetector;

        return $this;
    }

    /**
     * @param ProcessInterface[] $processList
     *
     * @return $this
     */
    public function setProcesses($processList)
    {
        foreach ($processList as $process) {
            $this->addProcess($process);
        }

        return $this;
    }

    /**
     * @param ProcessInterface $process
     *
     * @return $this
     */
    public function addProcess(ProcessInterface $process)
    {
        $this->processes[$process->getType()] = $process;

        return $this;
    }

    /**
     * @param mixed $data
     *
     * @throws Exception\ProcessorFailedException
     * @throws Exception\ProcessNotFoundException
     * @throws Exception\SourceNotValidException
     * @throws Exception\SourceNotReadableException
     * @throws Exception\ImportTypeNotDetectedException
     */
    public function process($data)
    {
        $this->checkProcessorState();
        $this->setStatusMessage('started processing');
        $input = $this->reader->read($data);
        $this->setStatusMessage('successfully read data');

        if ($this->typeDetector instanceof InputTypeDetectorInterface) {
            try {
                $type = $this->typeDetector->detect($input);
                if (null !== $this->logger) {
                    $this->logger->info(sprintf('Import: Input TypeDetector detected the type "%s"', $type));
                }
            } catch (\Exception $exception) {
                throw new Exception\ProcessorFailedException('An Exception occoured while detecting input type', 0, $exception);
            }
            $this->doProcess($type, $input->getData());
        } else {
            $this->doSingleRowProcess($input);
        }

        $this->setStatusMessage(sprintf('successfully imported %s rows', count($input->getData())));
    }

    /**
     * @param Input $input
     *
     * @throws Exception\SourceNotValidException
     * @throws Exception\ProcessorFailedException
     */
    protected function doSingleRowProcess(Input $input)
    {
        if (!$this->typeDetector instanceof RowTypeDetectorInterface) {
            throw new Exception\ProcessorFailedException(sprintf('Unknown TypeDetector supplied%s[detector] %s', PHP_EOL, get_class($this->typeDetector)));
        }

        $rowNumber = 0;
        foreach ($input->getData() as $row) {
            $type = $this->typeDetector->detectByRow($row, $input);
            if (null !== $this->logger) {
                $this->logger->info(sprintf('Import: Row TypeDetector detected the type "%s"', $type));
            }
            try {
                $this->doProcess($type, [$row]);
            } catch (Exception\SourceNotValidException $exception) {
                $exception->setRowNumber($exception->getRowNumber() + $rowNumber);
                throw $exception;
            }
            ++$rowNumber;
        }
    }

    /**
     * @param string $type
     * @param array $data
     *
     * @throws Exception\ProcessNotFoundException
     * @throws Exception\ProcessorFailedException
     */
    protected function doProcess($type, array $data)
    {
        if (!$this->hasProcessForType($type)) {
            throw new Exception\ProcessNotFoundException(sprintf('No process could be found for type%s[type] %s', PHP_EOL, $type));
        }
        $process = $this->getProcessForType($type);
        if (null !== $this->logger) {
            $this->logger->info(sprintf('Import: Found process "%s" for type "$s"', get_class($process), $type));
        }
        $data = $this->filter($data, $process);
        $process->getValidator()->validate($data);
        $process->getWriter()->write($data);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function hasProcessForType($type)
    {
        return isset($this->processes[$type]);
    }

    /**
     * @param string $type
     *
     * @return ProcessInterface
     */
    protected function getProcessForType($type)
    {
        return $this->processes[$type];
    }

    /**
     * @throws Exception\ProcessorFailedException
     */
    protected function checkProcessorState()
    {
        if (!$this->reader) {
            throw new Exception\ProcessorFailedException('Processor is missing reader');
        }

        if (!$this->typeDetector) {
            throw new Exception\ProcessorFailedException('Processor is missing type-detector');
        }

        if (empty($this->processes)) {
            throw new Exception\ProcessorFailedException('Processor is missing at least one process');
        }
    }

    /**
     * @param string $message
     */
    private function setStatusMessage($message)
    {
        $this->statusMessage = $message;
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    /**
     * @param array $data
     * @param ProcessInterface $process
     *
     * @throws Exception\ProcessorFailedException
     *
     * @return array
     */
    protected function filter(array $data, ProcessInterface $process)
    {
        if ($process instanceof FilterableProcessInterface) {
            $filter = $process->getFilter($data);
            if (is_callable($filter)) {
                if (null !== $this->logger) {
                    $this->logger->info('Import: Calling filter');
                }
                $data = call_user_func($filter, $data, $this->deleteNullValues);
            } else {
                throw new Exception\ProcessorFailedException('The Filter is not callable. Either make it callable or remove the filter-interface.');
            }
        }

        return $data;
    }

    /**
     * @param string $deleteNullValues
     */
    public function setDeleteNullValues($deleteNullValues)
    {
        $this->deleteNullValues = $deleteNullValues;
    }

}
