# cfc-spryker-content
`FirstSpirit Preview Content Module for Spryker`

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
use Crownpeak\Shared\FirstSpiritPreviewContent\FirstSpiritPreviewContentConstants;
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

// ----------- FirstSpirit Preview Content Configuration
$config[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_FRONTEND_API_SERVER_URL] = '<ADD Content Endpoint HOST (without parameters)>';
```
for local url the value can be:
```
http://host.docker.internal:3001/api/findPage
```

**Add namespace in Yves EventDispatcherDependencyProvider**

Add the following to your `src/Pyz/Yves/EventDispatcher/EventDispatcherDependencyProvider.php` file
```
use Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\EventDispatcher\FirstSpiritPreviewContentEventDispatcherPlugin;
```
and in the function `protected function getEventDispatcherPlugins(): array {` add the following line
```
...
new FirstSpiritPreviewContentEventDispatcherPlugin(),
```

**Add namespace in Yves TwigDependencyProvider**

Add the following to your `src/Pyz/Yves/Twig/TwigDependencyProvider.php` file
```
use Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig\FirstSpiritPreviewContentDataTwigFunction;
```
and in the function `protected function getTwigPlugins(): array {` add the following line
```
new FirstSpiritPreviewContentDataTwigFunction(),
```

**Add twig variable in template(s)**

Edit templates to include lines described below:


**Homepage template:** after this line for `src/Pyz/Yves/HomePage/Theme/default/views/home/home.twig` file:
```
{% block container %}
```
```
    {% set placeholder_sup_content = '' %}
    {% set placeholder_sub_content = '' %}
    {% set fsContentData = firstSpiritCfcContentScriptData(data.product.idProductAbstract, 'product', data.appLocale ) %}
    {% set contentData = [] %}
    {% set previewIdChildren = '' %}
    {% set sections_content = [] %}
    {% if fsContentData.items is not empty %}
        {% for items in fsContentData.items[0].children %}
            {% if items.name == 'stage' %}
                {% if items.children|length > 0 %}
                    {% for key, sections in items.children %}
                        {% set previewIdChildren = items.children[key].previewId %}
                        {% set contentData = items.children[key].data|json_encode() %}
                        {% set sections_content = sections_content|merge(['<div data-preview-id="' ~ previewIdChildren ~ '">' ~ contentData ~ '</div>']) %}
                        {% set placeholder_sup_content = sections_content|join(' ') %}
                    {% endfor %}
                {% endif %}
            {% endif %}
            {% if items.name == 'content' %}
                {% if items.children|length > 0 %}
                    {% for key, sections in items.children %}
                        {% set previewIdChildren = items.children[key].previewId %}
                        {% set contentData = items.children[key].data|json_encode() %}
                        {% set sections_content = sections_content|merge(['<div data-preview-id="' ~ previewIdChildren ~ '">' ~ contentData ~ '</div>']) %}
                        {% set placeholder_sub_content = sections_content|join(' ') %}
                    {% endfor %}
                {% endif %}
            {% endif %}
        {% endfor %}
    {% endif %}
...
```

**Product template:** after this line for `src/Pyz/Yves/ProductDetailPage/Theme/default/views/pdp/pdp.twig` file:
```
{% block content %}
```
```
    {% set placeholder_sup_content = '' %}
    {% set placeholder_sub_content = '' %}
    {% set fsContentData = firstSpiritCfcContentScriptData(data.product.idProductAbstract, 'product', data.appLocale ) %}
    {% set contentData = [] %}
    {% set previewIdChildren = '' %}
    {% set sections_content = [] %}
    {% if fsContentData.items is not empty %}
        {% for items in fsContentData.items[0].children %}
            {% if items.name == 'sup_content' %}
                {% if items.children|length > 0 %}
                    {% for key, sections in items.children %}
                        {% set previewIdChildren = items.children[key].previewId %}
                        {% set contentData = items.children[key].data|json_encode() %}
                        {% set sections_content = sections_content|merge(['<div data-preview-id="' ~ previewIdChildren ~ '">' ~ contentData ~ '</div>']) %}
                        {% set placeholder_sup_content = sections_content|join(' ') %}
                    {% endfor %}
                {% endif %}
            {% endif %}
            {% if items.name == 'sub_content' %}
                {% if items.children|length > 0 %}
                    {% for key, sections in items.children %}
                        {% set previewIdChildren = items.children[key].previewId %}
                        {% set contentData = items.children[key].data|json_encode() %}
                        {% set sections_content = sections_content|merge(['<div data-preview-id="' ~ previewIdChildren ~ '">' ~ contentData ~ '</div>']) %}
                        {% set placeholder_sub_content = sections_content|join(' ') %}
                    {% endfor %}
                {% endif %}
            {% endif %}
        {% endfor %}
    {% endif %}
...
```

**Catalog template:** after this line for `src/Pyz/Yves/CatalogPage/Theme/default/templates/page-layout-catalog/page-layout-catalog.twig` file:
```
{% block container %}
```
```
    {% set placeholder_sup_content = '' %}
    {% set placeholder_sub_content = '' %}
    {% set fsContentData = firstSpiritCfcContentScriptData(data.category.id_category, 'category', app.locale) %}
    {% set contentData = [] %}
    {% set previewIdChildren = '' %}
    {% set sections_content = [] %}
    {% if fsContentData.items is not empty %}
        {% for items in fsContentData.items[0].children %}
            {% if items.name == 'sup_content' %}
                {% if items.children|length > 0 %}
                    {% for key, sections in items.children %}
                        {% set previewIdChildren = items.children[key].previewId %}
                        {% set contentData = items.children[key].data|json_encode() %}
                        {% set sections_content = sections_content|merge(['<div data-preview-id="' ~ previewIdChildren ~ '">' ~ contentData ~ '</div>']) %}
                        {% set placeholder_sup_content = sections_content|join(' ') %}
                    {% endfor %}
                {% endif %}
            {% endif %}
            {% if items.name == 'sub_content' %}
                {% if items.children|length > 0 %}
                    {% for key, sections in items.children %}
                        {% set previewIdChildren = items.children[key].previewId %}
                        {% set contentData = items.children[key].data|json_encode() %}
                        {% set sections_content = sections_content|merge(['<div data-preview-id="' ~ previewIdChildren ~ '">' ~ contentData ~ '</div>']) %}
                        {% set placeholder_sub_content = sections_content|join(' ') %}
                    {% endfor %}
                {% endif %}
            {% endif %}
        {% endfor %}
    {% endif %}
...
```

**CMS page templates:** after this line for `src/Pyz/Shared/Cms/Theme/default/templates/placeholders-title-content/placeholders-title-content.twig` and
`src/Pyz/Shared/Cms/Theme/default/templates/placeholders-title-content-slot/placeholders-title-content-slot.twig` files:
```
{% block content %}
```
```
    {% set placeholder_sup_content = '' %}
    {% set placeholder_sub_content = '' %}
    {% set fsContentData = firstSpiritCfcContentScriptData(_view.idCmsPage, 'content', data.appLocale ) %}
    {% set contentData = [] %}
    {% set previewIdChildren = '' %}
    {% set sections_content = [] %}
    {% if fsContentData.items is not empty %}
        {% for items in fsContentData.items[0].children %}
            {% if items.name == 'sup_content' %}
                {% if items.children|length > 0 %}
                    {% for key, sections in items.children %}
                        {% set previewIdChildren = items.children[key].previewId %}
                        {% set contentData = items.children[key].data|json_encode() %}
                        {% set sections_content = sections_content|merge(['<div data-preview-id="' ~ previewIdChildren ~ '">' ~ contentData ~ '</div>']) %}
                        {% set placeholder_sup_content = sections_content|join(' ') %}
                    {% endfor %}
                {% endif %}
            {% endif %}
            {% if items.name == 'sub_content' %}
                {% if items.children|length > 0 %}
                    {% for key, sections in items.children %}
                        {% set previewIdChildren = items.children[key].previewId %}
                        {% set contentData = items.children[key].data|json_encode() %}
                        {% set sections_content = sections_content|merge(['<div data-preview-id="' ~ previewIdChildren ~ '">' ~ contentData ~ '</div>']) %}
                        {% set placeholder_sub_content = sections_content|join(' ') %}
                    {% endfor %}
                {% endif %}
            {% endif %}
        {% endfor %}
    {% endif %}
...
```
_**and in these lines for all templates mentioned above not including homepage:**_

add **{{ placeholder_sup_content }}** variable.
```
 <div style="margin: 20px; padding: 20px;" data-fcecom-slot-name="sup_content">
   
 </div>
```
```
<div style="margin: 20px; padding: 20px;" data-fcecom-slot-name="sup_content">
   {{ placeholder_sup_content | raw }}
 </div>
```

add **{{ placeholder_sub_content }}** variable.

```
 <div style="margin: 20px; padding: 20px;" data-fcecom-slot-name="sub_content">
 
 </div>
```
```
 <div style="margin: 20px; padding: 20px;" data-fcecom-slot-name="sub_content">
   {{ placeholder_sub_content | raw }}
 </div>
```

_For **homepage** should be:_

add **{{ placeholder_sup_content }}** variable.
```
<div style="margin: 20px; padding: 20px;" data-fcecom-slot-name="stage">
     
</div>
```
```
<div style="margin: 20px; padding: 20px;" data-fcecom-slot-name="stage">
   {{ placeholder_sup_content | raw }}
</div>
```

add **{{ placeholder_sub_content }}** variable.

```
<div style="margin: 20px; padding: 20px;" data-fcecom-slot-name="content">
 
</div>
```
```
<div style="margin: 20px; padding: 20px;" data-fcecom-slot-name="content">
    {{ placeholder_sub_content | raw }}
</div>
```

## Testing
To test a particular branch in your Spryker installation replace _{branchname}_ in the command below:
```
$ docker/sdk cli composer require ecom-espirit/cfc-spryker-content:dev-{branchname}
```