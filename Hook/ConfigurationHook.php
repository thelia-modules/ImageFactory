<?php
/*************************************************************************************/
/*      This file is part of the module ImageFactory                                 */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace ImageFactory\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class ConfigurationHook
 * @package ImageFactory\Hook
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class ConfigurationHook extends BaseHook
{
    /**
     * @param HookRenderEvent $event
     */
    public function onModuleConfigurationJs(HookRenderEvent $event)
    {
        $event->add($this->render(
            'image-factory/hook/configuration-js.html'
        ));
    }
}
