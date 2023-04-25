<?php
/**
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */

namespace Macareux\BulkPageMover\Console\Command;

use Concrete\Core\Console\Command;
use Macareux\BulkPageMover\Traits\PageMoveTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PageMoveCommand extends Command
{
    use PageMoveTrait;

    /**
     * @var bool|string|string[]|null
     */
    protected $pathFrom;
    /**
     * @var bool|string|string[]|null
     */
    protected $pathTo;

    protected function configure(): void
    {
        $this->setName('md:page:move')
            ->setDescription('Move multiple pages at once')
            ->setAliases(['md:move-pages'])
            ->addEnvOption()
            ->addOption('from', 'f', InputOption::VALUE_REQUIRED, 'The path of the page to move')
            ->addOption('to', 't', InputOption::VALUE_REQUIRED, 'The path of the page to move to')
        ;
    }

    /**
     * @throws \Concrete\Core\Error\UserMessageException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $this->pathFrom = $input->getOption('from');
        $this->pathTo = $input->getOption('to');

        $pages = $this->getPagesToMove();
        
        $progressBar = new ProgressBar($output, count($pages));
        $progressBar->setFormat('debug');
        $progressBar->start();
        
        foreach ($pages as $page) {
            if (is_object($page) && !$page->isError()) {
                $page->move($this->getPageTo());
                $progressBar->advance();
            }
        }

        return 0;
    }

    protected function getPathFrom(): string
    {
        return (string) $this->pathFrom;
    }

    protected function getPathTo(): string
    {
        return (string) $this->pathTo;
    }
}
