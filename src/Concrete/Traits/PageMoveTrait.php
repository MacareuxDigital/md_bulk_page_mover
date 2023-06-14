<?php
/**
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */

namespace Macareux\BulkPageMover\Traits;

use Concrete\Core\Error\UserMessageException;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\PageList;

trait PageMoveTrait
{
    /**
     * @var string
     */
    protected $pathFrom;
    /**
     * @var string
     */
    protected $pathTo;
    protected $pageFrom;
    protected $pageTo;

    /**
     * @return array
     * @throws \Concrete\Core\Error\UserMessageException
     */
    public function getPagesToMove(): array
    {
        if (!$this->getPathFrom() || !$this->getPathTo()) {
            throw new UserMessageException(t('Please input page path to move from and to.'));
        }

        if (!$this->getPageFrom() || $this->getPageFrom()->isError()) {
            throw new UserMessageException(t('Page path %s not found.', $this->getPathFrom()));
        }

        if (!$this->getPageTo() || $this->getPageTo()->isError()) {
            throw new UserMessageException(t('Page path %s not found.', $this->getPathTo()));
        }

        $list = new PageList();
        $list->ignorePermissions();
        $list->includeInactivePages();
        $list->includeSystemPages();
        $list->filterByPath($this->getPathFrom());

        return $list->getResults();
    }

    /**
     * @return Page|null
     */
    public function getPageFrom(): ?Page
    {
        if (!$this->pageFrom) {
            $this->pageFrom = Page::getByPath($this->getPathFrom());
        }

        return $this->pageFrom;
    }

    /**
     * @return Page|null
     */
    public function getPageTo(): ?Page
    {
        if (!$this->pageTo) {
            $this->pageTo = Page::getByPath($this->getPathTo());
        }

        return $this->pageTo;
    }

    abstract protected function getPathFrom(): string;
    abstract protected function getPathTo(): string;
}