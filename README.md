# Scoutola

A networking tool for active and former scouts of the swiss scout association, built on top of [osclass](https://osclass-classifieds.com/).

https://scoutola.scout.ch

## Usage of this repository

This repo includes all the bits and parts needed to adapt osclass for our usecase. Included are:

- **oidc plugin**: A customly built plugin to allow login via OIDC providers, specifically the [MiData](https://db.scout.ch) 
- **silverscouts plugin**: A plugin to handle all the other, non-generic customizations for scoutola, including retrieving data from the midata.
- **sigma theme patch**: A set of patches to be applied on top of the default sigma theme.
- **Development environnment**: Devcontainer, automatic osclass installation, helperscripts.

It must not include any of osclass' copyrighted sourcecode.

## Development

To start development, follow these steps:

1. Reopen the repo as a devcontainer in VS Code
1. In a terminal inside the devcontainer, run `.devcontainer/setup_osclass.sh`
1. Install composer dependencies with `cd plugins/oidc; composer install`
1. Run `php -S 0.0.0.0:8000` to start the php webserver
1. Open http://localhost:8000 in your browser and run osclass setup. Use the following db parameters:
  - Host: *db*
  - Username: *osclass*
  - Password: *osclass*
  - Database: *osclass*

### Theme

The theme is stored as a patch to allow for update later on in the lifecycle.

- *base/* is the base for the patches. Do not touch it unless theres a new version of osclass
- *merged/* is the merged theme. Do not add it to sourcecontrol. You may use it to develop changes to the theme.
- *overlay* is the folder for the changes, specifically *theme.patch*. Place additional files in here to be copied over.
- *bin/merge.sh* is used to create a fresh merged theme. It will overwrite all changes in *merged/*. 
- *bin/create_patch.sh* is used to create a patch from the changes in *merged/*. It will overwrite the existing theme.patch. Run this before committing to sourcecontrol.

### Plugins

#### OIDC

- The oidc plugin has composer dependencies. Install them with `composer install` from the plugins folder.
- The oidc plugin also relies on a database. If you make changes to the schema, you must create a migration in the *migrations/* folder as an SQL-file. Increment the version number to ensure it's applied correctly.

## Deployment

osclass must be installed on the target host, please refer to the [official documentation](https://osclass-classifieds.com/installation). 

### Theme

- Create a backup of the existing theme
- `rsync -avz --delete ./theme/merged/ <HOST>:sites/scoutola.scout.ch/oc-content/themes/sigma/`

### Plugins 

- `rsync -avz --delete ./plugins/oidc/ <HOST>:sites/scoutola.scout.ch/oc-content/plugins/oidc/`
- `rsync -avz --delete ./plugins/silverscouts/ <HOST>:sites/scoutola.scout.ch/oc-content/plugins/silverscouts/`


### Manual steps after initial setup

- [ ] Enable permalinks: https://scoutola.scout.ch/oc-admin/index.php?page=settings&action=permalinks
- [ ] Copy the theme and the plugins from this repo to the destination.
- [ ] Install the oidc and silverscout plugins: https://scoutola.scout.ch/oc-admin/index.php?page=plugins
- [ ] Configure the oidc plugin: https://scoutola.scout.ch/oc-admin/index.php?page=plugins&action=renderplugin&file=oidc/admin.php
- [ ] Disable self registiation: https://scoutola.scout.ch/oc-admin/index.php?page=users&action=settings
- [ ] Find and set fontawesome icons for categories: https://fontawesome.com/v5/search?s=solid%2Cregular / https://scoutola.scout.ch/oc-admin/index.php?page=categories
- [ ] Set correct language names: https://scoutola.scout.ch/oc-admin/index.php?page=languages
- [ ] Manually change translations: https://scoutola.scout.ch/oc-admin/index.php?page=translations
- [ ] Change item settings: https://scoutola.scout.ch/oc-admin/index.php?page=items&action=settings

## Contribute

Please coordinate with the responsible people if any contributions are welcome at this time.

Ask @diegosteiner technical support on the sourcecode.
