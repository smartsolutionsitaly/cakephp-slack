<?php
/**
 * cakephp-slack (https://github.com/smartsolutionsitaly/cakephp-slack)
 * Copyright (c) 2019 Smart Solutions S.r.l. (https://smartsolutions.it)
 *
 * Slack client and helpers for CakePHP
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @category  cakephp-plugin
 * @package   cakephp-slack
 * @author    Lucio Benini <dev@smartsolutions.it>
 * @copyright 2019 Smart Solutions S.r.l. (https://smartsolutions.it)
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://smartsolutions.it Smart Solutions
 * @since     1.0.0
 */

namespace SmartSolutionsItaly\CakePHP\Slack\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Http\Exception\UnauthorizedException;
use SmartSolutionsItaly\CakePHP\Slack\Http\Client\SlackClient;

/**
 * Component for Slack's slash command.
 * @package SmartSolutionsItaly\CakePHP\Slack\Controller\Component
 * @author Lucio Benini <dev@smartsolutions.it>
 * @since 1.0.0
 */
class SlackComponent extends Component
{
    /**
     * Slack Client instance.
     * @var SlackClient
     */
    protected $_client;

    /**
     * {@inheritDoc}
     * @see \Cake\Controller\Component::initialize()
     */
    public function initialize(array $config = [])
    {
        parent::initialize($config);

        $request = $this->getController()->getRequest();

        if ((bool)Configure::read('Settings.bots.slack.enable') && $request->getData('token') == Configure::read('Slack.token')) {
            $this->_client = new SlackClient;
            $this->_client->setHook($request->getData('response_url'));

            return true;
        } else {
            throw new UnauthorizedException;
        }
    }

    /**
     * Sends a text message to Slack and returns a serialized response.
     * @param string $text The text to send.
     * @return mixed A serialized response.
     */
    public function respond(string $text)
    {
        $this->_client->text($text);

        $this->getController()->set('content', [
            'response_type' => 'in_channel',
            'text' => $text
        ]);
        $this->getController()->set('_serialize', ['response_type', 'text']);

        return null;
    }
}
