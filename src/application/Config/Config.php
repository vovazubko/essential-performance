<?php
/**
 * Application Framework
 *
 * @category   Application
 * @package    EssentialPerformance\Config
 * @author     Vova Zubko <vozubko@gmail.com>
 * @copyright  2019 Vova Zubko
 * @version    1.0
 * @since      File available since Release 1.0
 * @file       src/application/Core/Config/Config.php
 * @date       10/20/2019
 * @link       https://vauko.com
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPLv2
 */

declare(strict_types=1);

namespace EssentialPerformance\Config;


class Config
{
    public $version = '0.0.1';

    public $actions = [];

    public $filters = [];

    public function __construct()
    {
        $this->actions[] = [
            'name'     => 'wp_enqueue_scripts',
            'callback' => [
                'controller' => 'FrontendController',
                'action'     => 'addEnqueueScripts'
            ]
        ];

        $this->actions[] = [
            'name'     => 'wp_footer',
            'callback' => [
                'controller' => 'FrontendController',
                'action'     => 'addFooter'
            ]
        ];

        $this->filters[] = [
            'name'     => 'the_content',
            'callback' => [
                'controller' => 'LazyLoadController',
                'action'     => 'updateContentImages'
            ],
            'priority' => 100,
        ];

    }

}