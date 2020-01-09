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
    public $version = '0.0.3';

    public $textDomain = 'essential-performance';

    public $options = [
        'lazy_load'       => 0,
        'browser_caching' => 0,
    ];

    public $actions = [];

    public $filters = [];

    public function __construct()
    {
        // Load options
        $options = get_option('essential_settings');

        if (is_array($options)) {
            foreach ($options as $key => $value) {
                $this->options[$key] = $value;
            }
        }


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


        if ($this->options['lazy_load'] === 1) {
            $this->filters[] = [
                'name'     => 'the_content',
                'callback' => [
                    'controller' => 'LazyLoadController',
                    'action'     => 'updateContentImages'
                ],
                'priority' => 100,
            ];
        }


        // Backend actions
        $this->actions[] = [
            'name'     => 'admin_init',
            'callback' => [
                'controller' => 'BackendController',
                'action'     => 'initCallback'
            ]
        ];

        $this->actions[] = [
            'name'     => 'admin_menu',
            'callback' => [
                'controller' => 'BackendController',
                'action'     => 'settingsMenuCallback'
            ]
        ];
    }

}