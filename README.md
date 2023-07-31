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
$config[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_CONTENT_SCRIPT_URL] = '<ADD Content Endpoint HOST (without parameters)>'; // e.g. 'http://xxx.xxx.xxx.xxx:3001/api/findPage', has to be reachable from within the Docker container
$config[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_RENDERED_TEMPLATE_CACHE_DURATION] = '<Cache duration for rendered templates>'; // Value in seconds, default is 7 days, 0 to disable
$config[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_API_RESPONSE_CACHE_DURATION] = '<Cache duration for FE API responses>'; // Value in seconds, default is 5 minutes, not used in preview mode. 0 to disable
$config[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_DISPLAY_BLOCK_RENDER_ERRORS] = true;

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
use Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig\FirstSpiritPreviewContentAttributesTwigFunction;
```
and in the function `protected function getTwigPlugins(): array {` add the following line
```
new FirstSpiritPreviewContentDataTwigFunction(),
new FirstSpiritPreviewContentAttributesTwigFunction(),
```

**Add twig variable in template(s)**

Edit templates to include lines described below:


**Product template:** after this line for `src/Pyz/Yves/ProductDetailPage/Theme/default/views/pdp/pdp.twig` file:
```
{% block headStyles %}
    {{ parent() }}
    <link itemprop="url" href="{{ data.productUrl }}">
{% endblock %}

{% block attributes %}
    {{ parent() }}
    {{ firstSpiritAttributes(data.product.idProductAbstract, "product", "product", data.title, data.appLocale) }}
{% endblock %}
{% block pageInfo %}
// ...

{% block content %}
    <div class="container__inner">
        {{ firstSpiritContent('sup_content') | raw }}
    // ...

    </div>

    {{ firstSpiritContent('sub_content') | raw }}

{% endblock %}
```

**Catalog template:** after this line for `src/Pyz/Yves/CatalogPage/Theme/default/templates/page-layout-catalog/page-layout-catalog.twig` file:
```
{% define data = {
    // ...
} %}

{% block attributes %}
    {{ parent() }}
    {{ firstSpiritAttributes(data.category.id_category, "category", "category", data.title, data.appLocale) }}
{% endblock %}

{% block container %}

// ...

<main class="container__inner">

        {{ firstSpiritContent('sup_content') | raw }}

        // ...

        </main>
        {{ firstSpiritContent('sub_content') | raw }}
    </div>
{% endblock %}
```

**CMS page templates:** after this line for `src/Pyz/Shared/Cms/Theme/default/templates/placeholders-title-content/placeholders-title-content.twig` and
`src/Pyz/Shared/Cms/Theme/default/templates/placeholders-title-content-slot/placeholders-title-content-slot.twig` files:
```
{% define data = {
    // ...
} %}

{% block attributes %}
    {{ parent() }}
    {{ firstSpiritAttributes(data.idCmsPage, "content", "content", data.title, data.appLocale) }}
{% endblock %}

// ...

{% block content %}
    {{ firstSpiritContent('sup_content') | raw }}

// ...

    {{ firstSpiritContent('sub_content') | raw }}

    <div class="box">
        {% cms_slot 'slt-8' with {
            idCmsPage: data.idCmsPage,
        } %}
    </div>
{% endblock %}
```


**CMS page templates:** after this line for `src/Pyz/Yves/HomePage/Theme/default/views/home/home.twig` files:
```
{% extends template('page-layout-main') %}


{% block attributes %}
    {{ parent() }}
    {{ firstSpiritAttributes("homepage", "content", "homepage", data.title, data.appLocale) }}
{% endblock %}

// ...

            {% block content %}
                {{ firstSpiritContent('stage') | raw }}

                {% cms_slot 'slt-3' %}

                {{ firstSpiritContent('content') | raw }}

            {% endblock %}
        </main>
    </div>
{% endblock %}

```


## Testing
To test a particular branch in your Spryker installation replace _{branchname}_ in the command below:
```
$ docker/sdk cli composer require ecom-espirit/cfc-spryker-content:dev-{branchname}
```