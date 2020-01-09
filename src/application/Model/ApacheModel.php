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
        return '# BEGIN EssentialPerformanceExpires\n' . "\n" .
            '<FilesMatch "\.(jpg|jpeg|png|gif|webp|svg|js|css|webm|mp4|ogg|ico|pdf|woff|woff2|otf|ttf|eot|x-html|xml|flv|swf)(\.gz)?$">' . "\n" .
            '<IfModule mod_headers.c>' . "\n" .
            'Header set Expires "max-age=A31536000, public"' . "\n" .
            'Header set Connection keep-alive' . "\n" .
            'Header unset ETag' . "\n" .
            'FileETag None' . "\n" .
            '</IfModule>' . "\n" .
            '<IfModule mod_expires.c>' . "\n" .
            'ExpiresActive On' . "\n" .
            'ExpiresDefault A0' . "\n" .

            'AddType application/font-woff2 .woff2' . "\n" .
            'AddType application/x-font-opentype .otf' . "\n" .

            'ExpiresByType application/javascript A31536000' . "\n" .
            'ExpiresByType application/x-javascript A31536000' . "\n" .

            'ExpiresByType application/font-woff2 A31536000' . "\n" .
            'ExpiresByType application/x-font-opentype A31536000' . "\n" .
            'ExpiresByType application/x-font-truetype A31536000' . "\n" .

            'ExpiresByType image/png A31536000' . "\n" .
            'ExpiresByType image/jpg A31536000' . "\n" .
            'ExpiresByType image/jpeg A31536000' . "\n" .
            'ExpiresByType image/gif A31536000' . "\n" .
            'ExpiresByType image/webp A31536000' . "\n" .
            'ExpiresByType image/ico A31536000' . "\n" .
            'ExpiresByType image/svg+xml A31536000' . "\n" .

            'ExpiresByType text/css A31536000' . "\n" .
            'ExpiresByType text/javascript A31536000' . "\n" .

            'ExpiresByType video/ogg A31536000' . "\n" .
            'ExpiresByType video/mp4 A31536000' . "\n" .
            'ExpiresByType video/webm A31536000' . "\n" .
            '</IfModule>' . "\n" .
            '</FilesMatch>' . "\n" .
            '# END EssentialPerformanceExpires' . "\n";
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
        $pattern = '/#\s?BEGIN\s?' . $marker . '.*?#\s?END\s?' . $marker . '\n/s';
        $this->content = preg_replace($pattern, '', $this->content, -1, $count);

        return $count > 0;
    }

}