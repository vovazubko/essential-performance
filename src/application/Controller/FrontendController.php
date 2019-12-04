<?php
/**
 * Application Framework
 *
 * @category   Application
 * @package    EssentialPerformance\Controller
 * @author     Vova Zubko <vozubko@gmail.com>
 * @copyright  2019 Vova Zubko
 * @version    1.0
 * @since      File available since Release 1.0
 * @file       src/application/Core/Controller/FrontendController.php
 * @date       10/20/2019
 * @link       https://vauko.com
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPLv2
 */

declare(strict_types=1);

namespace EssentialPerformance\Controller;


use EssentialPerformanceFramework\Core\Controller;
use EssentialPerformanceFramework\Core\InitInterface;

class FrontendController extends Controller implements InitInterface
{
    public function init()
    {
    }

    public function addEnqueueScripts()
    {
        wp_enqueue_script('essential-lazy-load', $this->appURL . 'assets/js/lazyload/lazyload.min.js', [], '2.0.0-rc.2', true);
        wp_enqueue_script('essential-main', $this->appURL . 'assets/js/main.js', ['jquery', 'essential-lazy-load'], $this->Config->version, true);
    }

    public function addFooter()
    {
    }
}