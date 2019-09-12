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

defined('_JEXEC') or die;

use Joomla\CMS\Input\Input as CMSWebInput;

/**
 * PlgSystemXYSeoLinkRelPagination class.
 *
 * @since       1.0
 */
class PlgSystemXTPerfectSEOPagination extends JPlugin
{
    /**
     * Application object.
     *
     * @var CMSApplication
     *
     * @since  1.0
     */
    protected $app;

    private $passed = false;

    /**
     * Executes before Joomla! renders its content.
     *
     * @return mixed
     */
    public function onAfterRoute()
    {
        if ($this->passed || $this->app->isAdmin() || !$this->isPagination()) {
            return;
        }

        $this->passed = true;
        require_once 'PaginationHelper.php';

        if ($this->params->get('enhance_description_with_page')) {
            XTPaginationHelper::enhanceDescriptionWithPage();
        }

        if ($this->params->get('enhance_title_with_page')) {
            XTPaginationHelper::enhanceTitleWithPage();
        }

        if ($this->params->get('enhance_with_noindex')) {
            $paramNoindex = $this->params->get('enhance_with_noindex');
            XTPaginationHelper::enhanceWithNoIndex($paramNoindex);
        }

        if ($this->params->get('enhance_with_prevnextlinks')) {
            XTPaginationHelper::enhanceWithPrevNextLinks();
        }
    }

    private function isPagination()
    {
        $input = new CMSWebInput;

        return (int) $input->getVar('start') > 0 || (int) $input->getVar('limitstart') > 0;
    }
}
