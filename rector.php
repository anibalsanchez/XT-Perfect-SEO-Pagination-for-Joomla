<?php

declare(strict_types=1);

/*
 * @package     XT Perfect SEO Pagination
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2012-2025 Extly, CB. All rights reserved.
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 *
 * @see         https://www.extly.com
 */

use Rector\Config\RectorConfig;
use Utils\Rector\Rector\LegacyCallToJClassToJModernRector;

require_once '/home/anibalsanchez/7_Projects/Platform/rector-rule-joomla-legacy-to-joomla-modern/src/Rector/LegacyCallToJClassToJModernRector.php';

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/plugins',
    ])
    ->withSkip([
        '*/platform/*',
        '*/vendor/*',
        '*/node_modules/*',
        '*Legacy*',
        'plugins/system/plg_system_xtperfectseopagination/J3-Pagination.php',
    ])
    ->withPhpSets(php74: true)
    ->withPreparedSets(
        codeQuality: true,
        codingStyle: true,
        earlyReturn: true,
        instanceOf: true,
        naming: true,
        // TODO: Enable typed properties
        typeDeclarations: false,
    )
    ->withRules([
        LegacyCallToJClassToJModernRector::class,
    ]);
