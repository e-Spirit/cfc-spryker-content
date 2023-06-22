# cfc-spryker-content
`FirstSpirit Preview CaaS Module for Spryker`

## Installation
**Composer**

Add the following to your `composer.json` file
```
"repositories": [
        {
            "url": "https://github.com/ecom-espirit/cfc-spryker-content.git",
            "type": "vcs"
        }
    ],
```
and run
```
$ composer require ecom-espirit/cfc-spryker-content
```
## Configuration
**Add the configuration to your Spryker B2C application**

Add the following to your `config/Shared/config_default.php` file
```
use Crownpeak\Shared\FirstSpiritPreviewCaaS\FirstSpiritPreviewCaaSConstants;
```
Add Crownpeak to the project namespaces in config/Shared/config_default.php:
```
$config[KernelConstants::PROJECT_NAMESPACES] = [
 ...
 'Crownpeak',
];
```
and then:
```
...

// ----------- FirstSpirit Preview CaaS Configuration
$config[FirstSpiritPreviewCaaSConstants::FIRSTSPIRIT_PREVIEW_CAAS_SCRIPT_URL] = '<ADD CaaS Endpoint HOST (without parameters)>';
```


**Add namespace in Yves TwigDependencyProvider**

Add the following to your `src/Pyz/Yves/Twig/TwigDependencyProvider.php` file
```
use Crownpeak\Yves\FirstSpiritPreviewCaaS\Plugin\Twig\FirstSpiritPreviewCaaSDataTwigPlugin;
```
and in the function `protected function getTwigPlugins(): array {` add the following line
```
new FirstSpiritPreviewCaaSDataTwigPlugin(),
```

**Add twig variable in Main page > page-layout-main.twig**

Add the following to your `src/Pyz/Yves/ShopUi/Theme/default/templates/page-layout-main/page-layout-main.twig` file at the end of the document
```
{% block footerScripts %}
    {{ parent() }}
    {{ firstSpiritCfcScriptUrl|raw }}
{% endblock %}
```

## Testing
To test a particular branch in your Spryker installation replace _{branchname}_ in the command below:
```
$ docker/sdk cli composer require ecom-espirit/cfc-spryker-content:dev-{branchname}
```