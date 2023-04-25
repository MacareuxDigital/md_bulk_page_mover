<?php
/**
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */

namespace Macareux\BulkPageMover\Command;

use Concrete\Core\Page\Page;
use Macareux\BulkPageMover\ConcreteCMS;
use Macareux\BulkPageMover\Mail\Command\CoreCommand;

if (ConcreteCMS::isV9()) {
    class_alias('Concrete\Core\Foundation\Command\Command', 'Macareux\BulkPageMover\Mail\Command\CoreCommand');
} else {
    class_alias('Macareux\BulkPageMover\Command', 'Macareux\BulkPageMover\Mail\Command\CoreCommand');
}

class PageMoveCommand extends CoreCommand
{
    /**
     * @var int
     */
    protected $pageId;
    /**
     * @var int
     */
    protected $pageIdTo;

    public function __construct(int $pageId, int $pageIdTo)
    {
        $this->pageId = $pageId;
        $this->pageIdTo = $pageIdTo;
    }

    public function getPageId(): int
    {
        return $this->pageId;
    }

    public function getPageIdTo(): int
    {
        return $this->pageIdTo;
    }
}
