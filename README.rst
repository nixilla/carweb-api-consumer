CarweB API consumer in PHP
==========================

|Travis|_

.. |Travis| image:: https://travis-ci.org/nixilla/carweb-api-consumer.png?branch=master
.. _Travis: https://travis-ci.org/nixilla/carweb-api-consumer

Installation
------------

Via composer:

.. code-block:: json

    {
        "require": {
            "nixilla/carweb-api-consumer": "*"
        }
    }

Tests
-----

This is copy/paste command

.. code:: sh

    git clone https://github.com/nixilla/carweb-api-consumer.git && \
    cd carweb-api-consumer && \
    mkdir bin && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=bin && \
    ./bin/composer.phar install --dev && \
    ./bin/phpunit
