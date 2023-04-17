<?php
/**
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */

namespace Macareux\BulkPageMover\Console\Command;

use Concrete\Core\Console\Command;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\PageList;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PageMoveCommand extends Command
{
    /**
     * @var bool|string|string[]|null
     */
    protected $fromPath;
    /**
     * @var bool|string|string[]|null
     */
    protected $toPath;

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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $this->fromPath = $input->getOption('from');
        $this->toPath = $input->getOption('to');

        $toPage = Page::getByPath($this->toPath);
        if (!$toPage || $toPage->isError()) {
            $output->writeln('The destination page does not exist');
            return 1;
        }

        $list = new PageList();
        $list->ignorePermissions();
        $list->includeInactivePages();
        $list->filterByPath($this->fromPath);
        $pages = $list->getResults();
        
        $progressBar = new ProgressBar($output, count($pages));
        $progressBar->setFormat('debug');
        $progressBar->start();

        foreach ($pages as $page) {
            if (is_object($page) && !$page->isError()) {
                $page->move($toPage);
                $progressBar->advance();
            }
        }

        return 0;
    }
}
