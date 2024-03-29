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

defined('_JEXEC') || exit;

use Extly\Pagination\XTPaginationHelper;
use Joomla\CMS\Application\AdministratorApplication;
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

    public function onAfterRoute()
    {
        if ($this->passed || $this->app instanceof AdministratorApplication || !$this->isPagination()) {
            return;
        }

        require_once 'XTPaginationHelper.php';

        if ($this->params->get('enhance_with_prevnextlinks', 1)) {
            $xtPaginationHelper = new XTPaginationHelper();
            $xtPaginationHelper->enhanceWithPrevNextLinks();
        }
    }

    /**
     * Executes before Joomla! renders its content.
     */
    public function onBeforeCompileHead()
    {
        if ($this->passed || $this->app instanceof AdministratorApplication || !$this->isPagination()) {
            return;
        }

        $this->passed = true;
        $xtPaginationHelper = new XTPaginationHelper();

        if ($this->params->get('enhance_description_with_page', 1)) {
            $xtPaginationHelper->enhanceDescriptionWithPage();
        }

        if ($this->params->get('enhance_title_with_page', 1)) {
            $xtPaginationHelper->enhanceTitleWithPage();
        }

        if ($this->params->get('enhance_with_noindex', 1)) {
            $paramNoindex = (int) $this->params->get('enhance_with_noindex');
            $xtPaginationHelper->enhanceWithNoIndex($paramNoindex);
        }

        if ($this->params->get('enhance_with_canonicallink', 1)) {
            $xtPaginationHelper->enhanceWithCanonicalLink();
        }
    }

    private function isPagination()
    {
        $input = new CMSWebInput();

        return (int) $input->getVar('start') > 0 || (int) $input->getVar('limitstart') > 0;
    }
}
