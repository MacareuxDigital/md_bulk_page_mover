<?php
/**
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */
namespace Concrete\Package\MdBulkPageMover\Job;

use Concrete\Core\Application\Application;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Job\QueueableJob;
use Concrete\Core\Page\Page;
use Macareux\BulkPageMover\Traits\PageMoveTrait;
use ZendQueue\Message as ZendQueueMessage;
use ZendQueue\Queue as ZendQueue;

class BulkMovePages extends QueueableJob
{
    use PageMoveTrait;

    /**
     * @var \Concrete\Core\Application\Application
     */
    protected $app;
    /**
     * @var \Concrete\Core\Config\Repository\Repository
     */
    protected $config;

    public function getJobName(): string
    {
        return t('Bulk Move Pages');
    }

    public function getJobDescription(): string
    {
        return t('Bulk move pages from underneath one page path to another.');
    }

    public function __construct(Application $app, Repository $config)
    {
        parent::__construct();
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * @throws \Concrete\Core\Error\UserMessageException
     */
    public function start(ZendQueue $q): void
    {
        $pages = $this->getPagesToMove();
        /** @var Page $page */
        foreach ($pages as $page) {
            $q->send($page->getCollectionID());
        }
    }

    public function finish(ZendQueue $q): string
    {
        return t('All tasks processed');
    }

    public function processQueueItem(ZendQueueMessage $msg): void
    {
        $page = Page::getByID($msg->body);
        $pageTo = $this->getPageTo();
        if (is_object($page) && !$page->isError() && is_object($pageTo) && !$pageTo->isError()) {
            $page->move($pageTo);
        }
    }

    /**
     * @return string
     */
    protected function getPathFrom(): string
    {
        if (!$this->pathFrom) {
            $this->pathFrom = (string)$this->config->get('md_bulk_page_mover::settings.path_from');
        }

        return $this->pathFrom;
    }

    /**
     * @return string
     */
    protected function getPathTo(): string
    {
        if (!$this->pathTo) {
            $this->pathTo = (string)$this->config->get('md_bulk_page_mover::settings.path_to');
        }

        return $this->pathTo;
    }
}
