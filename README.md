# Statamic Hooker 
![GitHub tag (latest SemVer)](https://img.shields.io/github/tag/makkinga/statamic-hooker.svg?label=version) [![StyleCI Status](https://github.styleci.io/repos/157406962/shield?style=flat&branch=master)](https://github.styleci.io/repos/157406962) [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A simple little addon for making webhooks.

<a href="https://www.buymeacoffee.com/k5DfS3Q" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;" ></a>

## Installation
Download or clone this repo and move the `Hooker` folder into the `~/site/addons` directory of your project.

Run the update command from the root of your project to install the addon depenencies:
```shell
php please update:addons
```
Done!

## Usage
### Adding a new hook
In your project's CP navigate to `Addons > Hooker`.

Click the `Hook +` button to make a new webhook.

In the replicater entry you just made, enter the endpoint that should be triggered.

### Selecting events
Under the endpoint input you see all the Statamic events that can be lissened to. Every event you select here will trigger your hook.

### Hide keys
If for example you want to hide personal information these keys can be hidden from the request to your endpoint.
To do this, simply add the field's key to the `Hidden keys` list.

## Examples
### Mailchimp
Lets say you want to send a newsletter each time a new product has been added to your shop or send a welcoming email each time a new user has been added.

In that case you make a hook with the Mailchimp trigger endpoint as URL and select eighter `Saved > entry` or `Saved > User`.

Each time you save an Entry or a User one of the hooks will be triggered and it will make a request to the given URL.

In order to know what happened on the receiving side, Statamic Hooker will send all the information about the event with it's request

## Feature wishlist
- The option to select a specific Collection, Entry, User etc
