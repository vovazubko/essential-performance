<?php
/**************************************************************************
 * Essential Performance - Wordpress Plugin
 *
 * @package     Essential Performance - Wordpress Plugin
 * @author      Vova Zubko <vozubko@gmail.com>
 * @copyright   2020 Vova Zubko <vozubko@gmail.com>
 * @license     GPL-2.0-or-later
 * @site        https://vauko.com
 * @file        Apache.php
 * @date        1/6/2020
 */

declare(strict_types=1);

namespace EssentialPerformance\Model;


use EssentialPerformanceFramework\Core\Model;
use EssentialPerformanceFramework\Exception\AppException;

class ApacheModel extends Model
{
    private $content  = '';
    private $filePath = false;

    /**
     * Read htaccess file
     *
     * @return bool
     */
    public function read()
    {
        $this->filePath = ABSPATH . '.htaccess';

        try {
            if (file_exists($this->filePath) && is_readable($this->filePath)) {
                $htaccess = file_get_contents($this->filePath);

                if ($htaccess !== false) {
                    $this->content = $htaccess;

                    return true;
                }
            }
        } catch (AppException $e) {
            // TODO: add log file for app.
        }

        $this->filePath = false;

        return false;
    }


    public function write()
    {
        if ($this->filePath === false) {
            return false;
        }

        try {
            if (file_exists($this->filePath) && is_writable($this->filePath)) {
                $htaccess = file_put_contents($this->filePath, $this->content);

                if ($htaccess !== false) {
                    $this->content = $htaccess;

                    return true;
                }
            }
        } catch (AppException $e) {
            // TODO: add log file for app.
        }

        return false;
    }

    /**
     * Get htaccess content
     *
     * Note: content is dynamic, use read to get actual content from disk
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    public function getExpires()
    {
        return '# BEGIN EssentialPerformanceExpires' . PHP_EOL .
            '<FilesMatch "\.(jpg|jpeg|png|gif|webp|svg|js|css|webm|mp4|ogg|ico|pdf|woff|woff2|otf|ttf|eot|x-html|xml|flv|swf)(\.gz)?$">' . PHP_EOL .
            '<IfModule mod_headers.c>' . PHP_EOL .
            'Header set Expires "max-age=A31536000, public"' . PHP_EOL .
            'Header set Connection keep-alive' . PHP_EOL .
            'Header unset ETag' . PHP_EOL .
            'FileETag None' . PHP_EOL .
            '</IfModule>' . PHP_EOL .
            '<IfModule mod_expires.c>' . PHP_EOL .
            'ExpiresActive On' . PHP_EOL .
            'ExpiresDefault A0' . PHP_EOL .

            'AddType application/font-woff2 .woff2' . PHP_EOL .
            'AddType application/x-font-opentype .otf' . PHP_EOL .

            'ExpiresByType application/javascript A31536000' . PHP_EOL .
            'ExpiresByType application/x-javascript A31536000' . PHP_EOL .

            'ExpiresByType application/font-woff2 A31536000' . PHP_EOL .
            'ExpiresByType application/x-font-opentype A31536000' . PHP_EOL .
            'ExpiresByType application/x-font-truetype A31536000' . PHP_EOL .

            'ExpiresByType image/png A31536000' . PHP_EOL .
            'ExpiresByType image/jpg A31536000' . PHP_EOL .
            'ExpiresByType image/jpeg A31536000' . PHP_EOL .
            'ExpiresByType image/gif A31536000' . PHP_EOL .
            'ExpiresByType image/webp A31536000' . PHP_EOL .
            'ExpiresByType image/ico A31536000' . PHP_EOL .
            'ExpiresByType image/svg+xml A31536000' . PHP_EOL .

            'ExpiresByType text/css A31536000' . PHP_EOL .
            'ExpiresByType text/javascript A31536000' . PHP_EOL .

            'ExpiresByType video/ogg A31536000' . PHP_EOL .
            'ExpiresByType video/mp4 A31536000' . PHP_EOL .
            'ExpiresByType video/webm A31536000' . PHP_EOL .
            '</IfModule>' . PHP_EOL .
            '</FilesMatch>' . PHP_EOL .
            '# END EssentialPerformanceExpires' . PHP_EOL;
    }

    /**
     * Add new htaccess rule
     *
     * @param $marker
     * @param $rule
     *
     * @return bool
     */
    public function addRule($marker, $rule)
    {
        $pattern = '/BEGIN\s*' . $marker . '/';

        if (!preg_match($pattern, $this->content)) {
            $this->content = $rule . $this->content;

            return true;
        }

        return false;
    }

    /**
     * Delete htaccess rule
     *
     * @param $marker
     *
     * @return bool
     */
    public function deleteRule($marker)
    {
        $count = 0;
        $pattern = '/#\s?BEGIN\s?' . $marker . '.*?#\s?END\s?' . $marker . '/s';
        $this->content = preg_replace($pattern, '', $this->content, -1, $count);

        return $count > 0;
    }

}