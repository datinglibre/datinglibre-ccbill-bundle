# datinglibre-ccbill-bundle

![Build Status](https://github.com/datinglibre/datinglibre-ccbill-bundle/actions/workflows/datinglibre-ccbill-bundle.yml/badge.svg)

This bundle wraps functionality of the [datinglibre/ccbill](https://github.com/datinglibre/ccbill) library in a Symfony service, adds some Symfony commands, and allows CCBill authentication to be configured by a parent Symfony application.

## Installation

In your Symfony applications's root directory, run:

    composer require datinglibre/datinglibre-ccbill-bundle

Add the following line to your Symfony application `bundles.php` file:

    DatingLibre\CcBillBundle\DatingLibreCcBillBundle::class => ['all' => true]

## Configuration

In the `config/packages` directory, create the following file

    datinglibre_ccbill.yaml

Obtain your API user's details from CCBill, and enter them into this file:

    dating_libre_cc_bill:
        username: 'datinglibre'
        clientAccount: '132435'
        clientSubAccount: '0001'

Create a placeholder environmental variable for the password in `.env`:

    CCBILL_PASSWORD=

If you are using this bundle as part of [DatingLibre](https://github.com/datinglibre/datinglibre) then encrypt your CCBill password using `ansible-vault`: 

    ansible-vault encrypt_string --vault-password-file=~/vault_password p4ssw0rd

Enter the output into `deploy/inventories/staging/group_vars/webservers.yml` as `ccbill_password`.

This value will be used by the [update script](https://github.com/datinglibre/DatingLibre/wiki/Updating) when it generates and uploads the `env.local` file.

You can test that the bundle and credentials have been installed correctly, by trying to run the `app:ccbill:status` command, which tries to retrieve the status of a subscription. 

You will need to give your webserver's `IP` address to CCBill, so they can place it on the whitelist for your account.

Run the `app:ccbill:status` command on your webserver:

    root@datinglibre:~# cd /var/www/datinglibre
    root@datinglibre:/var/www/datinglibre# su datinglibre
    datinglibre@datinglibre:/var/www/datinglibre$ ./bin/console app:ccbill:status 123
    The given subscription was not found for the account the merchant was authenticated on

If you receive any other response, ie. related to failed authentication, then the bundle has not been installed correctly.

## Licence

Copyright 2020-2021 DatingLibre.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


