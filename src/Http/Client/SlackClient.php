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

namespace SmartSolutionsItaly\CakePHP\Slack\Http\Client;

use Cake\Core\Configure;
use Cake\Http\Client;

/**
 * Slack client.
 * @package SmartSolutionsItaly\CakePHP\Slack\Http\Client
 * @author Lucio Benini <dev@smartsolutions.it>
 * @since 1.0.0
 */
class SlackClient
{
    /**
     * HTTP Client instance.
     * @var \Cake\Http\Client
     */
    protected $_client;

    /**
     * The API hook.
     * @var string
     */
    protected $_hook;

    /**
     * Constructor.
     * Configures the base client.
     */
    public function __construct()
    {
        $this->_client = new Client;
        $this->_hook = Configure::read('Slack.hook');
    }

    /**
     * Sends a text message to the Slack channel.
     * @param string $text The message to send.
     * @return bool A value indicating whether the message has been sent.
     */
    public function text(string $text): bool
    {
        return $this->send(['text' => $text]);
    }

    /**
     * Sends the given body to the Slack channel.
     * @param array $body The body to send.
     * @return bool A value indicating whether the body has been sent.
     */
    public function send(array $body): bool
    {
        try {
            $res = $this->_client->post(
                $this->getHook(),
                json_encode($body),
                ['type' => 'json']
            );

            $status = $res->getStatusCode();

            return $status >= 200 && $status < 300;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * Gets the current hook.
     * @return string The current hook.
     */
    public function getHook()
    {
        return $this->_hook;
    }

    /**
     * Sets the current hook.
     * @param string $hook The hook to set.
     * @return SlackClient The current instance.
     */
    public function setHook(string $hook): SlackClient
    {
        $this->_hook = $hook;

        return $this;
    }
}
