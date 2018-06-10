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
 * @link       http://www.dynamiccodes.com/dynaportx
 * @since      File available since Release 0.2.0
 */

/**
 * HTML Class.
 *
 * A simple class to provide basic View related methods.
 *
 * @category    Libraries
 *
 * @author      Prasad Nayanajith
 *
 * @link        https://github.com/pnm1231/DynaPort-X/wiki/HTML-library
 */
class HTML
{
    /**
     * Convert the URI to URL.
     *
     * @param string $uri
     *
     * @return string
     */
    public function url($uri = '/')
    {
        return GLBL_URL.'/'.ltrim($uri, '/');
    }

    /**
     * Convert URI to an anchor tag.
     *
     * @param string $uri
     * @param string $text
     * @param string $title
     * @param string $target
     *
     * @return string
     */
    public function urlT($uri = '/', $text = 'Link', $title = null, $target = null)
    {
        $html = '<a ';
        $html .= 'href="'.$this->url($uri).'" ';
        if ($title) {
            $html .= 'title="'.$title.'" ';
        }
        if ($target) {
            $html .= 'target="'.$target.'" ';
        }
        $html .= '>'.$text.'</a>';

        return $html;
    }

    /**
     * Convert URI to full JS URL.
     *
     * @param string $file
     *
     * @return string
     */
    public function js($file)
    {
        return $this->commonStatic('js', $file);
    }

    /**
     * Convert URI to a script tag.
     *
     * @param string $file
     *
     * @return string
     */
    public function jsT($file)
    {
        return $this->commonStaticT('js', $file);
    }

    /**
     * Convert URI to full CSS URL.
     *
     * @param string $file
     *
     * @return string
     */
    public function css($file)
    {
        return $this->commonStatic('css', $file);
    }

    /**
     * Convert URI to a link tag.
     *
     * @param string $file
     *
     * @return string
     */
    public function cssT($file)
    {
        return $this->commonStaticT('css', $file);
    }

    /**
     * Convert URI to full image URL.
     *
     * @param string $file
     *
     * @return string
     */
    public function img($file)
    {
        return $this->commonStatic('images', $file);
    }

    /**
     * Convert URI to an img tag.
     *
     * @param string $file
     * @param int    $width
     * @param int    $height
     * @param string $alt
     *
     * @return string
     */
    public function imgT($file, $width = 0, $height = 0, $alt = null)
    {
        return $this->commonStaticT('images', $file, $width, $height, $alt);
    }

    /**
     * Convert URI to full image URL.
     *
     * @param string $file
     *
     * @return string
     */
    public function image($file)
    {
        return $this->commonStatic('images', $file);
    }

    /**
     * Convert URI to an img tag.
     *
     * @param string $file
     * @param int    $width
     * @param int    $height
     * @param string $alt
     *
     * @return string
     */
    public function imageT($file, $width = 0, $height = 0, $alt = null)
    {
        return $this->commonStaticT('images', $file, $width, $height, $alt);
    }

    /**
     * Convert URI to a specific static file URL.
     *
     * @param string $type
     * @param string $file
     *
     * @return string
     */
    private function commonStatic($type, $file)
    {
        $url = '';
        if (strpos($file, 'http') !== 0) {
            $url .= GLBL_URL.'/static/'.$type.'/';
        }
        $url .= ltrim($file, '/');
        if (($type == 'js' || $type == 'css') && !strpos($file, '.js')) {
            $url .= '.'.$type;
        }

        return $url;
    }

    /**
     * Convert URI to a specific static tag.
     *
     * @param string $type
     * @param string $file
     * @param int    $width
     * @param int    $height
     * @param string $alt
     *
     * @return string
     */
    private function commonStaticT($type, $file, $width = 0, $height = 0, $alt = null)
    {
        $url = $this->commonStatic($type, $file);
        $html = '';
        if ($type == 'img') {
            $html .= '<img src="'.$url.'" ';
            if ($width) {
                $html .= 'width="'.$width.'" ';
            }
            if ($height) {
                $html .= 'height="'.$height.'" ';
            }
            if ($alt) {
                $html .= 'alt="'.addslashes($alt).'" ';
            }
            $html .= '/>';
        } elseif ($type == 'css') {
            $html .= '<link type="text/css" rel="stylesheet" href="'.$url.'" />';
        } elseif ($type == 'js') {
            $html .= '<script type="text/javascript" src="'.$url.'"></script>';
        }

        return $html;
    }
}
