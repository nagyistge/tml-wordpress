<p align="center">
  <img src="https://raw.github.com/tml/tml/master/doc/screenshots/tmllogo.png">
</p>

Tml Plugin For Wordpress
=====================

This plugin uses Tml PHP Client SDK to enable inline translations of WordPress posts and page contents.

[![Latest Stable Version](https://poser.pugx.org/tml/tml-wordpress-plugin/v/stable.png)](https://packagist.org/packages/tml/tml-wordpress-plugin)
[![Dependency Status](https://www.versioneye.com/user/projects/52e4b4a3ec1375b57600000c/badge.png)](https://www.versioneye.com/user/projects/52e4b4a3ec1375b57600000c)


Installation
==================

Tml WordPress Plugin can be installed using the composer dependency manager. If you don't already have composer installed on your system, you can get it using the following command:

        $ cd YOUR_APPLICATION_FOLDER
        $ curl -s http://getcomposer.org/installer | php


Create composer.json in the root folder of your application, and add the following content:

        {
            "minimum-stability": "dev",
            "require": {
                "composer/installers": "v1.0.6",
                "tml/tml-wordpress-plugin": "dev-master"
            }
        }

This tells composer that your application requires tml-wordpress-plugin to be installed.

Now install Tml WordPress plugin by executing the following command:


        $ php composer.phar install


The installation will put the Tml WordPress plugin inside the wp-content/plugins/tml-wordpress-plugin folder.
At the same time, all other dependencies and libraries will be placed in the vendor folder and the WordPress plugin will refer to them through relative path.


Integration
==================

Now we can active the plugin by logging into to WordPress with an admin account and navigate to the Plugins section.

You should now see the Tml plugin as one of the options.

Click on the "Activate" link. You should see now a new section on the left bar called "Tml".

Before proceeding further, please visit http://tmlhub.com, register as a new user and create a new application.

Once you have created a new application, go to the security tab in the application administration section and copy your application key and secret.

Now you can go back to your WordPress and provide your application details in the Tml configuration section.

After you save the changes, you can add a language selector widget to your WordPess UI by visiting the Appearance > Widgets section.

The Tml Language Selector allows users to change languages of WordPress and your posts.

Now you are ready to invite translators and translate your blogs. By enabling inline translations, you can translate entire paragraphs inline:

Once the inline translations are disabled, your site will contibue to remain translated:


Links
==================

* Register on TranslationExchange.com: https://translationexchange.com

* Read TranslationExchange's documentation: http://wiki.translationexchange.com

* Visit TranslationExchange's blog: http://blog.translationexchange.com

* Follow TranslationExchange on Twitter: https://twitter.com/translationx

* Connect with TranslationExchange on Facebook: https://www.facebook.com/translationexchange

* If you have any questions or suggestions, contact us: info@translationexchange.com


Copyright and license
==================

Copyright (c) 2015, Translation Exchange

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


