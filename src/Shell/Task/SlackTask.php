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

namespace SmartSolutionsItaly\CakePHP\Slack\Shell\Task;

use Cake\Console\Shell;
use Cake\Core\Configure;
use SmartSolutionsItaly\CakePHP\Slack\Http\Client\SlackClient;

/**
 * Slack shell.
 * @package SmartSolutionsItaly\CakePHP\Slack\Shell\Task
 * @author Lucio Benini <dev@smartsolutions.it>
 * @since 1.0.0
 */
class SlackTask extends Shell
{
    protected $_client;

    /**
     * {@inheritDoc}
     * @see \Cake\Controller\Controller::initialize()
     */
    public function initialize()
    {
        parent::initialize();

        $this->_client = new SlackClient;
    }

    /**
     * Main shell command.
     * @return null
     */
    public function main()
    {
        if (!(bool)Configure::read('Settings.bots.slack.enable')) {
            $this->abort(__('{0}: Service disabled.', 'Slack'));

            return false;
        }

        return null;
    }

    /**
     * Sends a text to the Slack's bot.
     * @param string $text The text to send.
     * @return bool A value indicating whether the message has been sent.
     */
    public function text($text)
    {
        if ($this->_client->text((string)$text)) {
            $this->success(__('Text successfully sent to Slack bot'));

            return true;
        } else {
            $this->abort(__('Failed'));

            return false;
        }
    }

    /**
     * Gets the option parser instance and configures it.
     * @return ConsoleOptionParser The option parser instance.
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser->setDescription(__('This shell provides a service for Slack.'));

        $parser->addSubcommand('text', [
            'help' => __('Sends the given text to Slack.'),
            'parser' => [
                'description' => [
                    __('Sends the given text to Slack.')
                ],
                'arguments' => [
                    'text' => [
                        'help' => __('The text to send.'),
                        'required' => true
                    ]
                ]
            ]
        ]);

        return $parser;
    }
}
