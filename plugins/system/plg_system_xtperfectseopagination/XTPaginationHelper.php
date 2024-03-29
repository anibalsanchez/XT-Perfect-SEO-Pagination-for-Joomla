<?php

/*
 * @package     XT Perfect SEO Pagination
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2012-2024 Extly, CB. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html  GNU/GPLv2
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
        $isJ4orJ5 = file_exists(JPATH_LIBRARIES.'/bootstrap.php');

        if ($isJ4orJ5) {
            $isJ4 = file_exists(JPATH_LIBRARIES.'/classmap.php');

            if ($isJ4) {
                require_once 'J4-Pagination.php';

                return;
            }

            require_once 'J5-Pagination.php';

            return;
        }

        require_once 'J3-Pagination.php';
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
            $start = $input->getInt('start');
        }

        return $start;
    }
}
