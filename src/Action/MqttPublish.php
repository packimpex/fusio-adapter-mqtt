<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2020 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Adapter\Mqtt\Action;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\Exception\ConfigurationException;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

use PhpMqtt\Client\MqttClient;

/**
 * Mqtt
 *
 * @author  Tobias Soltermann <tobias.soltermann@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class MqttPublish extends ActionAbstract
{
    public function getName()
    {
        return 'MQTT-Publish';
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        $connection = $this->getConnection($configuration);
        $topic = $configuration->get('topic');
        try {
            $topic = $request->get('topic');
        } catch (\Exception $e) {}

        $body = $request->get('body');

        $qos = $configuration->get('qos') ?? 0;
        try {
            $qos = $request->get('qos');
        } catch (\Exception $e) {}
        $connection->publish($topic, $body, $qos);

        return $this->response->build(200, [], [
            'success' => true,
            'message' => 'Message successful published',
        ]);
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $builder->add($elementFactory->newConnection('connection', 'Connection', 'The MQTT connection which should be used'));
        $builder->add($elementFactory->newInput('topic', 'Topic', 'text', 'The MQTT topic to publish on. Can be overridden setting the attribute "topic" on the requests JSON'));
        $builder->add($elementFactory->newInput('qos', 'QoS', 'number', 'The QoS Value to use. Can be overridden setting the attribute "qos" on the requests JSON'));
    }


    protected function getConnection(ParametersInterface $configuration): MqttClient
    {
        $connection = $this->connector->getConnection($configuration->get('connection'));
        if (!$connection instanceof MqttClient) {
            throw new ConfigurationException('Given connection must be a MQTT connection');
        }

        return $connection;
    }
}
