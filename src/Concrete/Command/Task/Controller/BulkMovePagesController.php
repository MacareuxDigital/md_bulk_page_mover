<?php
/**
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */

namespace Macareux\BulkPageMover\Command\Task\Controller;

use Concrete\Core\Command\Batch\Batch;
use Concrete\Core\Command\Task\Input\Definition\Definition;
use Concrete\Core\Command\Task\Input\Definition\Field;
use Concrete\Core\Command\Task\Input\InputInterface;
use Concrete\Core\Command\Task\Runner\BatchProcessTaskRunner;
use Concrete\Core\Command\Task\Runner\TaskRunnerInterface;
use Concrete\Core\Command\Task\TaskInterface;
use Concrete\Core\Page\Page;
use Macareux\BulkPageMover\Command\PageMoveCommand;
use Macareux\BulkPageMover\ConcreteCMS;
use Macareux\BulkPageMover\Traits\PageMoveTrait;

if (ConcreteCMS::isV9()) {
    class_alias('Concrete\Core\Command\Task\Controller\AbstractController', 'Macareux\BulkPageMover\Command\Task\Controller\CoreController');
} else {
    class_alias('Macareux\BulkPageMover\AbstractController', 'Macareux\BulkPageMover\Command\Task\Controller\CoreController');
}
class BulkMovePagesController extends  CoreController
{
    use PageMoveTrait;

    public function getName(): string
    {
        return t('Bulk Move Pages');
    }

    public function getDescription(): string
    {
        return t('Bulk move pages from underneath one page path to another.');
    }

    public function getInputDefinition(): ?Definition
    {
        $definition = new Definition();
        $definition->addField(
            new Field(
                'pathFrom',
                t('Path From'),
                t('The path to move pages from. e.g. /about/our-team'),
                true
            )
        );

        $definition->addField(
            new Field(
                'pathTo',
                t('Path To'),
                t('The path to move pages to. e.g. /about/team'),
                true
            )
        );

        return $definition;
    }

    /**
     * @throws \Concrete\Core\Error\UserMessageException
     */
    public function getTaskRunner(TaskInterface $task, InputInterface $input): TaskRunnerInterface
    {
        $this->pathFrom = $input->hasField('pathFrom') ? $input->getField('pathFrom')->getValue(): '';
        $this->pathTo = $input->hasField('pathTo') ? $input->getField('pathTo')->getValue() : '';

        $batch = Batch::create();
        $pages = $this->getPagesToMove();
        /** @var Page $page */
        foreach ($pages as $page) {
            $batch->add(new PageMoveCommand($page->getCollectionID(), $this->getPageTo()->getCollectionID()));
        }

        return new BatchProcessTaskRunner($task, $batch, $input, t('Moving Pages From %s To %s...', $this->getPathFrom(), $this->getPathTo()));
    }

    protected function getPathFrom(): string
    {
        return $this->pathFrom;
    }

    protected function getPathTo(): string
    {
        return $this->pathTo;
    }
}
