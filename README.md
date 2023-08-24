# Mautic Cloudzapi Plugin
This plugin replaces the SMS channel and allows you to send messages to Cloudzapi
using the CloudZapi API (https://cloudzapi.com).
Intended for >= Mautic 4.0

Read more:
https://cloudzapi.com/mautic-integration

## Installation by console
1. Download the plugin, unzip in your plugins folder
2. Rename the folder to MauticCloudzapiBundle
3. `php bin/console mautic:plugins:reload`

## Usage
1. Go to your **Plugins** in Mautic
2. You should see new Cloudzapi plugin in the list, click and publish it.
3. Go to https://cloudzapi.com, create a account and see how you can get your credentials.
4. This plugin overrides your SMS transport. In your **Configuration > Text message settings** select Cloudzapi as default transport


