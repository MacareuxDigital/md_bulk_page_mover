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

        return $this->getFilteredList()->getResults();
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

    protected function getFilteredList(): PageList
    {
        $list = new PageList();
        $list->ignorePermissions();
        $list->includeInactivePages();
        $list->includeSystemPages();
        $list->filterByPath($this->getPathFrom());

        // Apply additional filters
        $filters = $this->getFilters();
        if (is_array($filters)) {
            $list = $this->applyFilters($list, $filters);
        }

        return $list;
    }

    protected function getFilters()
    {
        return app('config')->get('md_bulk_page_mover::settings.filters');
    }

    protected function applyFilters(PageList $list, array $filters): PageList
    {
        // Apply attribute filters
        if (isset($filters['attributes'])) {
            foreach ($filters['attributes'] as $key => $value) {
                if (is_array($value)) {
                    $list->filterByAttribute($key, $value['value'], $value['comparison']);
                } else {
                    // Default comparison is '='
                    $list->filterByAttribute($key, $value);
                }
            }
        }

        // Apply creation date filter
        if (isset($filters['date_added'])) {
            $startDate = $filters['date_added']['start'];
            $endDate = $filters['date_added']['end'];
            $list->filterByDateAdded($startDate, '>=');
            $list->filterByDateAdded($endDate, '<=');
        }

        // Apply modification date filter
        if (isset($filters['date_modified'])) {
            $startDate = $filters['date_modified']['start'];
            $endDate = $filters['date_modified']['end'];
            $list->filterByDateLastModified($startDate, '>=');
            $list->filterByDateLastModified($endDate, '<=');
        }

        // Apply public date filter
        if (isset($filters['date_public'])) {
            $startDate = $filters['date_public']['start'];
            $endDate = $filters['date_public']['end'];
            $list->filterByPublicDate($startDate, '>=');
            $list->filterByPublicDate($endDate, '<=');
        }

        // Apply full text keywords filter
        if (isset($filters['full_text_keywords'])) {
            $list->filterByFulltextKeywords($filters['full_text_keywords']);
        }

        // Apply keywords filter
        if (isset($filters['keywords'])) {
            $list->filterByKeywords($filters['keywords']);
        }

        // Apply page type ID filter
        if (isset($filters['page_type_id'])) {
            $list->filterByPageTypeID($filters['page_type_id']);
        }

        // Apply page type handle filter
        if (isset($filters['page_type_handle'])) {
            $list->filterByPageTypeHandle($filters['page_type_handle']);
        }

        // Apply user ID filter
        if (isset($filters['user_id'])) {
            $list->filterByUserID($filters['user_id']);
        }

        return $list;
    }

    abstract protected function getPathFrom(): string;
    abstract protected function getPathTo(): string;
}