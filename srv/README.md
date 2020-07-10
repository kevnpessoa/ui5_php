# n-rest
Micro-library PHP to Rest API

## Clone, Run and Test
#### Clone and Run (Terminal)
git clone https://github.com/kevnpessoa/n-rest.git

cd n-rest/

php -S localhost:8002 -t public

#### Test (Browser)
http://localhost:8002/user

## Structure

#### app
- Controller
- Model

#### public
- index.php

#### src
- Artifacts of the library, as Controller, ORM, Renderer...
- App.php (core of library)

#### app.php
File bootstrap of library

#### router.php
Routes of app

#### env.php
Data of envinroment: DB, PATHs...
