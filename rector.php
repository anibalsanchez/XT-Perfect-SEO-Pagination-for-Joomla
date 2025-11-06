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

use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/plugins',
    ])
    ->withSkip([
        '*/vendor/*',
        '*/node_modules/*',
        '*Legacy*',
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
    );
