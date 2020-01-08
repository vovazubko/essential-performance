<?php
/**************************************************************************
 * Essential Performance - Wordpress Plugin
 *
 * @package     Essential Performance - Wordpress Plugin
 * @author      Vova Zubko <vozubko@gmail.com>
 * @copyright   2019 Vova Zubko <vozubko@gmail.com>
 * @license     GPL-2.0-or-later
 * @site        https://vauko.com
 * @file        BackendController.php
 * @date        12/16/2019
 */

declare(strict_types=1);

namespace EssentialPerformance\Controller;


use EssentialPerformance\Config\Config;
use EssentialPerformance\Model\ApacheModel;
use EssentialPerformanceFramework\Core\Controller;
use EssentialPerformanceFramework\Core\InitInterface;

/**
 * Class BackendController
 * @package EssentialPerformance\Controller
 * @property Config $Config
 * @property ApacheModel $ApacheModel
 */
class BackendController extends Controller implements InitInterface
{

    public function init()
    {
    }

    /**
     * Admin init hook
     */
    public function initCallback()
    {
        $this->registerSettings();

        // Set class property
        $this->options = get_option('essential_settings');
    }

    /**
     * Add settings menu
     */
    public function settingsMenuCallback()
    {
        // This page will be under "Settings"
        add_options_page(
            __('Essential Performance', $this->Config->textDomain),
            __('Essential Performance', $this->Config->textDomain),
            'manage_options',
            $this->Config->textDomain . '-settings',
            [
                $this,
                'settingsPageCallback'
            ]
        );
    }

    /**
     * Settings page callback
     */
    public function settingsPageCallback()
    {
        var_dump($this->options);
        echo $this->load->view('Backend/settings_page', []);
    }

    /**
     * Register settings in Wordpress
     */
    public function registerSettings()
    {
        register_setting(
            $this->Config->textDomain . '_option_group',
            'essential_settings',
            [$this, 'sanitizeSettings']
        );

        add_settings_section(
            $this->Config->textDomain . '_setting_section',
            __('Leverage Browser Caching for Images, CSS and JS', $this->Config->textDomain),
            [$this, 'sectionInfoCallback'],
            'essential_settings'
        );

        add_settings_field(
            'essential_settings_browser_caching',
            __('Leverage Browser Caching', $this->Config->textDomain),
            [$this, 'leverageBrowserCachingCallback'],
            'essential_settings',
            $this->Config->textDomain . '_setting_section'
        );
    }

    /**
     * Print the Section text
     */
    public function sectionInfoCallback()
    {
        _e('To leverage your browser\'s caching generally means that you can specify how long web browsers should keep images, CSS and JS stored locally. ' .
            'That way the user\'s browser will download less data while navigating through your pages, which will improve the loading speed of your website.', $this->Config->textDomain);
    }

    /**
     * Print the Field html
     */
    public function leverageBrowserCachingCallback()
    {
        $checked = $this->options['browser_caching'] ? 'checked' : '';
        echo '<input id="essential_settings_browser_caching" name="essential_settings[browser_caching]" type="checkbox" value="1" ' . $checked . ' />';
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     *
     * @return array
     */
    public function sanitizeSettings($input)
    {
        $inputSanitized = [];

        $this->load->model('Apache');
        $this->ApacheModel->read();

        if (isset($input['browser_caching'])) {
            $inputSanitized['browser_caching'] = 1;

            if (($this->options['browser_caching'] === 0) && $this->Apache->addRule('EssentialPerformanceExpires', $this->Apache->getExpires())) {
                $this->Apache->write();
            }
        } else {
            $inputSanitized['browser_caching'] = 0;

            if (($this->options['browser_caching'] === 1) && $this->ApacheModel->deleteRule('EssentialPerformanceExpires')) {
                $this->ApacheModel->write();
            }
        }

        return $inputSanitized;
    }
}