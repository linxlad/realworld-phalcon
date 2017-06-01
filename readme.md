# ![RealWorld Example App](logo.png)

> ### Phalcon 3 codebase containing real world examples (CRUD, auth, advanced patterns, etc) that adheres to the [RealWorld](https://github.com/gothinkster/realworld-example-apps) spec and API.


### [Demo]()&nbsp;&nbsp;&nbsp;&nbsp;[RealWorld](https://github.com/gothinkster/realworld)


This codebase was created to demonstrate a fully fledged fullstack application built with **Phalcon 3** including CRUD operations, authentication, routing, pagination, and more.

We've gone to great lengths to adhere to the **Phalcon 3** community styleguides & best practices.

For more information on how to this works with other frontends/backends, head over to the [RealWorld](https://github.com/gothinkster/realworld) repo.


# How it works

> TODO

# Getting started

## Installation

Please check the official Phalcon installation guide for requirements. [Official Documentation](https://docs.phalconphp.com/en/latest/reference/install.html)

Clone the repository

    git clone git@github.com:linxlad/realworld-phalcon.git

Switch to the repo folder

    cd realworld-phalcon

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

#### Local development with Docker

> There is a docker development environment included in this project in ./docker with Phalcon loaded and ready to go.

    cd ./docker
    
Edit the docker-compose.yml and change mysql environment variables if need to. 

    docker-compose up -d
    
You can now access the server at http://localhost:8080
    
#### Local development with Nanobox
    
> TODO    

### Continuous Integration
[![Build Status](https://scrutinizer-ci.com/g/linxlad/realworld-phalcon/badges/build.png?b=master)](https://scrutinizer-ci.com/g/linxlad/realworld-phalcon/build-status/master)

## Test Coverage
[![Code Coverage](https://scrutinizer-ci.com/g/linxlad/realworld-phalcon/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/linxlad/realworld-phalcon/?branch=master)

## Code Quality
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/linxlad/realworld-phalcon/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/linxlad/realworld-phalcon/?branch=master)