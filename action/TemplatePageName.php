<?php
/**
 * DokuWiki Plugin templatepagename (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Martin <martin@sound4.biz>
 */

// must be run within Dokuwiki
use dokuwiki\Extension\ActionPlugin;
use dokuwiki\Extension\Event;
use dokuwiki\Extension\EventHandler;

/**
 * Class action_plugin_templatepagename_TemplatePageName
 */
class action_plugin_templatepagename_TemplatePageName extends ActionPlugin {

    /**
     * Registers a callback function for a given event
     *
     * @param EventHandler $controller
     */
    public function register(EventHandler $controller) {

        $controller->register_hook('COMMON_PAGETPL_LOAD', 'BEFORE', $this, 'handleCommonPagetplLoad');

    }

    /**
     * Adjust the pagetemplate names
     *
     * @param Event $event
     */
    public function handleCommonPagetplLoad(Event $event) {
        global $conf;

        // from here is it almost the same code as inc/common.php pageTemplate
        // function (core dokuwiki) but vars name are adjusted to be used
        // within the plugin.

        $c_pagename = $this->getConf('current_pagename_tpl');
        $i_pagename = $this->getConf('inherited_pagename_tpl');

        $path = dirname(wikiFN($event->data['id']));

        if(@file_exists($path . '/' . $c_pagename . '.txt')) {
            $event->data['tplfile'] = $path . '/' . $c_pagename . '.txt';
        } else {
            // search upper namespaces for templates
            $len = strlen(rtrim($conf['datadir'], '/'));
            while(strlen($path) >= $len) {
                if(@file_exists($path . '/' . $i_pagename . '.txt')) {
                    $event->data['tplfile'] = $path . '/' . $i_pagename . '.txt';
                    break;
                }
                $path = substr($path, 0, strrpos($path, '/'));
            }
        }
    }

}
