Fusio-Adapter-Mqtt
=====

This package is heavily based on the AMQP adapter (https://github.com/apioo/fusio-adapter-amqp), written by Christoph Kappestein.

[Fusio] adapter which provides a connection to work with a MQTT based message 
queue. It uses the php-mqtt/client package. You can install the adapter 
with the following steps inside your Fusio project:

    composer require fusio/adapter-mqtt
    php bin/fusio system:register Fusio\Adapter\Mqtt\Adapter

[Fusio]: http://fusio-project.org/
