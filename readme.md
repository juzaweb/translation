## About
Translate provides in-browser editing of [Juzaweb translation](https://juzaweb.com/plugin/translation) files and integration with automatic translation services.

[![Total Downloads](https://img.shields.io/packagist/dt/juzaweb/translation.svg?style=social)](https://packagist.org/packages/juzaweb/translation)
[![GitHub Repo stars](https://img.shields.io/github/stars/juzaweb/translation?style=social)](https://github.com/juzaweb/translation)

## Install
- Go to `Admin -> Plugins -> Add new`
- Search and add plugin translation
- Activate plugin

## Features
- Built-in translation editor within admin
- Create and update language files directly in your theme or plugin
- Protected language directory for saving custom translations

## Usage
- Multiple language support for your Plugin... 

If you ever struggled to support multiple languages for your website. Juzaweb CMS supports you with a translation tool for faster and easier translate.

### Import translation
Find and create translation in your Plugin to `jw_translations` table

```shell
php artisan plugin:import-translation {plugin-name}
```

### Google Translate Plugin
Translate your plugin by Google Translate

```shell
php artisan plugin:google-translate {plugin-name} {source} {target}
```

This command will translate and add new translations to the `jw_translations` table via Google Translate.

E.x:
```shell
php artisan plugin:google-translate default en hi
```

### Export translation to file json

Export all translation to file json

```shell
php artisan plugin:export-translation {plugin-name}
```

If you want to export a specific language, add the 2nd param

```shell
php artisan plugin:export-translation {plugin-name} {language}
```

- Multiple language support for your Theme...
If you ever struggled to support multiple languages for your website. Juzaweb CMS supports you with a translation tool for faster and easier translate.

### Import translation
Find and create translation in your Theme to `jw_translations` table

```shell
php artisan theme:import-translation {theme-name}
```

### Google Translate Theme
Translate your theme by Google Translate

```shell
php artisan theme:google-translate {theme-name} {source} {target}
```

This command will translate and add new translations to the `jw_translations` table via Google Translate.

E.x:
```shell
php artisan theme:google-translate default en hi
```

### Export translation to file json

Export all translation to file json

```shell
php artisan theme:export-translation {theme-name}
```

If you want to export a specific language, add the 2nd param

```shell
php artisan theme:export-translation {theme-name} {language}
```

## Images
![Plugin and theme list](https://i.imgur.com/8ZQil9t.png)

![Language theme/plugin](https://i.imgur.com/IuW9BtR.png)

![Translate theme/pluign](https://i.imgur.com/EnD0j4c.png)
