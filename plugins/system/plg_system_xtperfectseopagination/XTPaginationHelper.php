<?php

/*
 * @package     XT Perfect SEO Pagination
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2019 Extly, CB. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html  GNU/GPLv2
 *
 * @see         https://www.extly.com
 */

namespace Extly\Pagination;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory as CMSFactory;
use Joomla\CMS\Input\Input as CMSWebInput;
use Joomla\CMS\Language\Text as CMSText;

class XTPaginationHelper
{
    public function enhanceTitleWithPage()
    {
        $app = CMSFactory::getApplication();
        $sitenamePagetitles = $app->getCfg('sitename_pagetitles');

        $doc = CMSFactory::getDocument();
        $currentTitle = $doc->getTitle();
        $pageLabel = $this->getPageLabel();

        if (1 === $sitenamePagetitles) {
            $newTitle = $currentTitle.' - '.$pageLabel;
            $doc->setTitle($newTitle);

            return;
        }

        if (2 === $sitenamePagetitles) {
            $sitename = $app->getCfg('sitename');
            $currentTitleNositename = str_replace($sitename, '', $doc->getTitle());
            $newTitle = $currentTitleNositename.$pageLabel.' - '.$sitename;
            $doc->setTitle($newTitle);

            return;
        }

        $newTitle = $currentTitle.' - '.$pageLabel;
        $doc->setTitle($newTitle);
    }

    public function enhanceDescriptionWithPage()
    {
        $doc = CMSFactory::getDocument();
        $currentDescription = $doc->getDescription();
        $currentTitle = $doc->getTitle();
        $pageLabel = $this->getPageLabel();

        $newDescription = ($currentDescription ? $currentDescription : $currentTitle).
            ' - '.$pageLabel;

        $doc->setDescription($newDescription);
    }

    public function enhanceWithNoIndex($paramNoindex)
    {
        $doc = CMSFactory::getDocument();
        $robotsDirective = (1 === $paramNoindex) ? 'noindex, follow' : 'noindex, nofollow';
        $doc->setMetaData('robots', $robotsDirective);
    }

    public static function enhanceWithPrevNextLinks()
    {
        $isJ4 = file_exists(JPATH_LIBRARIES.'/bootstrap.php');

        if ($isJ4) {
            require_once 'J4-Pagination.php';

            return;
        }

        require_once 'J3-Pagination.php';
    }

    public static function generateSeoLinkRelPagination($data)
    {
        $doc = CMSFactory::getDocument();

        if (isset($data->previous->link)) {
            $doc->addHeadLink($data->previous->link, 'prev');
        }

        if (isset($data->next->link)) {
            $doc->addHeadLink($data->next->link, 'next');
        }
    }

    private function getPageLabel()
    {
        $currentNumberPage = $this->getCurrentPageNumber();

        return CMSText::sprintf('JLIB_HTML_PAGE_CURRENT', $currentNumberPage);
    }

    private function getCurrentPageNumber()
    {
        $input = new CMSWebInput;
        $start = $input->getVar('start');

        return $start;
    }
}
