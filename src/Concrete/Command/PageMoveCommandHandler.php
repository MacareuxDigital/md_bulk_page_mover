<?php
/**
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */

namespace Macareux\BulkPageMover\Command;

use Concrete\Core\Page\Page;

class PageMoveCommandHandler
{
    public function __invoke(PageMoveCommand $command)
    {
        $page = Page::getByID($command->getPageId());
        $pageTo = Page::getByID($command->getPageIdTo());
        if ($page && !$page->isError() && $pageTo && !$pageTo->isError()) {
            $page->move($pageTo);
        }
    }
}
