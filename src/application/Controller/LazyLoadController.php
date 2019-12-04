<?php
/**************************************************************************
 * Essential Performance - Wordpress Plugin
 *
 * @package     Essential Performance - Wordpress Plugin
 * @author      Vova Zubko <vozubko@gmail.com>
 * @copyright   2019 Vova Zubko <vozubko@gmail.com>
 * @license     GPL-2.0-or-later
 * @site        https://vauko.com
 * @file        LazyLoadController.php
 * @date        11/29/2019
 */

declare(strict_types=1);

namespace EssentialPerformance\Controller;


use EssentialPerformanceFramework\Core\Controller;

class LazyLoadController extends Controller
{
    public function updateContentImages($content)
    {
        return preg_replace_callback('/(<\s*img[^>]+)(src\s*=\s*"[^"]+")([^>]+>)/i', [$this, 'updateImage'], $content);
    }

    public function updateImage($imageMatch)
    {
        $imageReplace = $imageMatch[1] . 'src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-src' . substr($imageMatch[2], 3) . $imageMatch[3];
        $imageReplace = preg_replace('/class\s*=\s*"/i', 'class="lazyload ', $imageReplace);
        $imageReplace = str_replace('srcset="', 'data-srcset="', $imageReplace);

        if (strpos($imageReplace, 'class="') === false) {
            $imageReplace = str_replace('>', 'class="lazyload">', $imageReplace);
        }

        return $imageReplace;
    }

}