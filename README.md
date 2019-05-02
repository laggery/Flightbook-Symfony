# Flightbook Symfony app
Flightbook is a personal logbook for paraglider and hang glider pilots. The flights must be registered manually and the logbook is not connected to any GPS.

Why is-it not connected to GPS?
There exist a lot of application that are connected to the GPS but often you don’t want to register the track but you just like to have a trace of the flight. For example: Tandem pilots

How is construct this project?
The entire project is construct in three parts.
- A symphony application – Current repository  
- An API for the mobile applications – https://github.com/laggery/Flightbook-API
- A Mobile application – https://github.com/laggery/Flightbook-MobileApp

You can also easily start all projects in one time by clone the main Flightbook repository - https://github.com/laggery/Flightbook

## History
I started with this project in 2013 and first it was used from some Friends and me. 2015 I published the first mobile application version and developed a Symfony web page. 2018 I decided to redevelop the mobile application with the Ionic Framework and make the entire project open source.

## Getting started
This application can easily be started in a Docker container.

Clone this repository
```bash
$ git clone git@github.com:laggery/Flightbook-Symfony.git
```

Install a mysql database and load the structure you find in the folder “db”

Complete the environment variable in the following Docker command and run it:
```bash
$ docker run  -e DATABASE_USER= -e DATABASE_NAME= -e DATABASE_HOST= -e DATABASE_PORT= -e DATABASE_PASSWORD= -e MAILER_ENCRYPTION= -e MAILER_PORT= -e MAILER_AUTHMODE=login -e SECRET= -p 8080:80 laggery/flightbook-symfony:latest
```

Your application is now available with the following link:
[http://\<your-docker-ip>:8080/](http://<your-docker-ip>:8080/)

## Security
If you discover security related issues, please email yannick.lagger@flightbook.ch instead of using the issue tracker.

## Licence
Copyright (C) 2013-2018 Yannick Lagger, Switzerland.
Flightbook is released under the [GPL3 License](https://opensource.org/licenses/GPL-3.0)

## Other used open source project
- LexikFormFilterBundle https://github.com/lexik/LexikFormFilterBundle
- JMSI18nRoutingBundle https://github.com/schmittjoh/JMSI18nRoutingBundle
- CMENGoogleChartsBundle https://github.com/cmen/CMENGoogleChartsBundle
- TwigSpreadsheetBundle https://github.com/MewesK/TwigSpreadsheetBundle

## Authors
- Yannick Lagger yannick.lagger@flightbook.ch


#Old-readme
==========

A Symfony project created on December 20, 2015, 9:09 pm.

@hack 
swfitmailer for php 5.6

vendor/swiftmailer/swiftmailer/lib/classes/Swift/Transport/StreamBuffer.php right before the "stream_socket_client" command at line 263:

$options['ssl']['verify_peer'] = FALSE;
$options['ssl']['verify_peer_name'] = FALSE;
