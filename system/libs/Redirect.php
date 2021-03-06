<?php

/**
 * DynaPort X.
 *
 * A simple yet powerful PHP framework for rapid application development.
 *
 * Licensed under BSD license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright  Copyright (c) 2012-2013 DynamicCodes.com (http://www.dynamiccodes.com/dynaportx)
 * @license    http://www.dynamiccodes.com/dynaportx/license   BSD License
 *
 * @link       https://github.com/pnm1231/DynaPort-X/wiki
 * @since      File available since Release 0.2.0
 */

/**
 * Redirect Class.
 *
 * The redirect class which handles all header redirects.
 *
 * @category    Libraries
 *
 * @author      Prasad Nayanajith
 *
 * @link        https://github.com/pnm1231/DynaPort-X/wiki/Redirect-library
 */
class Redirect
{
    /**
     * Redirect to a specific URL.
     *
     * @param string $uri       The URL or the Path
     * @param bool   $permanent The type of redirect
     */
    public static function go($uri, $permanent = false)
    {
        if (!preg_match('/^(http|https)\:/', $uri)) {
            $uri = ltrim($uri, '/');
            $uri = GLBL_URL.'/'.$uri;
        }
        if (!empty($uri) && filter_var($uri, FILTER_SANITIZE_URL)) {
            if (!headers_sent($file, $line)) {
                if ($permanent) {
                    header('HTTP/1.1 301 Moved Permanently');
                }
                header('location: '.$uri);
                exit;
            } else {
                new DPxError('Unable to redirect', 500, 'DPX.Lib.Redirect.go: Cannot redirect since the header is already sent in '.$file.' on line '.$line);
            }
        } else {
            new DPxError('Unable to redirect', 500, 'DPX.Lib.Redirect.go: The provided redirect URL is invalid');
        }
    }
}
