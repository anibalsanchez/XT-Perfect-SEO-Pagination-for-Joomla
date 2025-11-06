<?php

/*
 * @package     XT Perfect SEO Pagination
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2012-2025 Extly, CB. All rights reserved.
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 *
 * @see         https://www.extly.com
 */

namespace Extly\Pagination;

\defined('_JEXEC') || exit;

use Joomla\CMS\Factory as CMSFactory;
use Joomla\CMS\Input\Input as CMSWebInput;
use Joomla\CMS\Language\Text as CMSText;
use Joomla\CMS\Uri\Uri as CMSUri;

class XTPaginationHelper
{
    public function enhanceTitleWithPage()
    {
        $app = CMSFactory::getApplication();
        $sitenamePagetitles = (int) $app->getCfg('sitename_pagetitles');

        $doc = CMSFactory::getDocument();
        $currentTitle = $doc->getTitle();
        $pageLabel = $this->getPageLabel();

        // Sitename after
        if (2 === $sitenamePagetitles) {
            $sitename = $app->getCfg('sitename');
            $currentTitleNositename = str_replace($sitename, '', $doc->getTitle());
            $newTitle = $currentTitleNositename.$pageLabel.' - '.$sitename;
            $doc->setTitle($newTitle);

            return;
        }

        // Sitename before
        if (1 === $sitenamePagetitles) {
            $newTitle = $currentTitle.' - '.$pageLabel;
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

        $newDescription = trim($currentDescription ?: $currentTitle).
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
        $version = (int) JVERSION;

        switch ($version) {
            case 3:
                require_once __DIR__.'/J3-Pagination.php';
                break;
            case 4:
                require_once __DIR__.'/J4-Pagination.php';
                break;
            case 5:
                require_once __DIR__.'/J5-Pagination.php';
                break;
            case 6:
                require_once __DIR__.'/J6-Pagination.php';
                break;
        }
    }

    public static function enhanceWithCanonicalLink()
    {
        $doc = CMSFactory::getDocument();
        $doc->addHeadLink(CMSUri::getInstance()->toString(), 'canonical');
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
        $input = new CMSWebInput();

        $start = $input->getInt('limitstart');

        if (!$start) {
            return $input->getInt('start');
        }

        return $start;
    }
}
