The README.md file content is generated automatically, see [Magento module README.md](https://github.com/magento/devdocs/wiki/Magento-module-README.md) for more information.

# RCFerreira_AbandonedCart module

## Installation details

For installation this module is simple, only inform that command.

<b>composer require "rcferreira/module-abandoned-cart"</b><br />
<b>php bin/magento setup:upgrade</b><br />
<b>php bin/magento setup:di:compile</b><br />

## Extensibility

Extension developers can interact with the RCFerreira_AbandonedCart module. For more information about the Magento extension mechanism, see [Magento plug-ins](https://devdocs.magento.com/guides/v2.4/extension-dev-guide/plugins.html).

[The Magento dependency injection mechanism](https://devdocs.magento.com/guides/v2.4/extension-dev-guide/depend-inj.html) enables you to override the functionality of the RCFerreira_AbandonedCart module.

### Configuration

In Admin, go to Stores -> Configuration -> RCFERREIRA -> RCFerreira Abandoned Cart.<br />
There is a form with four fields.<br />
<b>Inform Name</b> is user name email sender<br />
<b>Inform Email</b> is user email sender</b>
<b>Inform Hour</b> is in that moment the plataform send a email to customer after his add products in the cart, if you don't want send after 1 hour you can leave it empty<br />
<b>Inform Minute</b> is in that moment the plataform send a email in minutes<br />

### How it works

It's module use cron to send email to customer for notification his that there is a abandoned cart.<br />
That email there is a link that redirect customer to site again.
