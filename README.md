# cfc-spryker-content
This module is part of the FirstSpirit Connect for Commerce Spryker integration.
It is responsible to render the content maintained in FirstSpirit.

## Installation
Add the following to your `composer.json` file
```json
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
Add the following to your configuration:
```php
use Crownpeak\Shared\FirstSpiritPreviewContent\FirstSpiritPreviewContentConstants;

// ...

$config[KernelConstants::PROJECT_NAMESPACES] = [
 // ...
 'Crownpeak',
];

// ...

// ----------- FirstSpirit Preview Content Configuration
$config[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_FRONTEND_API_SERVER_URL] = '<ADD Content Endpoint HOST (without parameters)>'; // e.g. 'http://xxx.xxx.xxx.xxx:3001/api/findPage', has to be reachable from within the Docker container
$config[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_RENDERED_TEMPLATE_CACHE_DURATION] = '<Cache duration for rendered templates>'; // Value in seconds, default is 7 days, 0 to disable
$config[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_API_RESPONSE_CACHE_DURATION] = '<Cache duration for FE API responses>'; // Value in seconds, default is 5 minutes, not used in preview mode. 0 to disable
$config[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_PREVIEW_DISPLAY_BLOCK_RENDER_ERRORS] = true;

// Configure template mapping for cfc-spryker-reference-components
$config[FirstSpiritPreviewContentConstants::FIRSTSPIRIT_SECTION_TEMPLATE_MAPPING] = [
  'text_image' => 'Crownpeak:FirstSpiritReferenceComponents/fs-text-image',
  '*' => 'Crownpeak:FirstSpiritReferenceComponents/fs-data-visualizer',
];

```

## Registering components
### Add namespace in Yves EventDispatcherDependencyProvider

Add the following to your `src/Pyz/Yves/EventDispatcher/EventDispatcherDependencyProvider.php` file:
```php
use Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\EventDispatcher\FirstSpiritPreviewContentEventDispatcherPlugin;

// ...

    protected function getEventDispatcherPlugins(): array
    {
        return [
            // ...
            new FirstSpiritPreviewContentEventDispatcherPlugin()
        ];
    }
```

### Add namespace in Yves TwigDependencyProvider

Add the following to your `src/Pyz/Yves/Twig/TwigDependencyProvider.php` file:

```php
// ...
use Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig\FirstSpiritPreviewContentDataTwigFunction;
use Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig\FirstSpiritPreviewContentAttributesTwigFunction;
use Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Twig\FirstSpiritPreviewContentGlobalsTwigFunction;

// ...

    protected function getTwigPlugins(): array
    {
        return [
            // ...
            new FirstSpiritPreviewContentDataTwigFunction(),
            new FirstSpiritPreviewContentAttributesTwigFunction(),
            new FirstSpiritPreviewContentGlobalsTwigPlugin(),
        ];
```

### Register router in Yves RouterDependencyProvider

Add the following to your `src/Pyz/Yves/Router/RouterDependencyProvider.php` file:

```php
// ...
use Crownpeak\Yves\FirstSpiritPreviewContent\Plugin\Route\FirstSpiritPreviewContentRoutePlugin;


// ...

    protected function getRouteProvider(): array
    {
        return [
            // ...
            new FirstSpiritPreviewContentRoutePlugin()
        ];
```

## Extend Twig templates

### Product template
Modify the `src/Pyz/Yves/ProductDetailPage/Theme/default/views/pdp/pdp.twig` file:
```twig
{% block headStyles %}
    {{ parent() }}
    <link itemprop="url" href="{{ data.productUrl }}">
{% endblock %}

{% block attributes %}
    {{ parent() }}
    {{ firstSpiritAttributes(data.product.idProductAbstract, "product", "product", data.title, data.appLocale) }}
{% endblock %}
{% block pageInfo %}

{# ... #}

{% block content %}
    <div class="container__inner">
        {{ firstSpiritContent('sup_content') | raw }}

        {# ... #}

    </div>

    {{ firstSpiritContent('sub_content') | raw }}

{% endblock %}
```

### Catalog template
Modify the `src/Pyz/Yves/CatalogPage/Theme/default/templates/page-layout-catalog/page-layout-catalog.twig` file:
```twig
{% define data = {
    ...
} %}

{% block attributes %}
    {{ parent() }}
    {{ firstSpiritAttributes(data.category.id_category, "category", "category", data.title, data.appLocale) }}
{% endblock %}

{% block container %}

{# ... #}

        <main class="container__inner">
            {{ firstSpiritContent('sup_content') | raw }}

            {# ... #}
        </main>
        {{ firstSpiritContent('sub_content') | raw }}
    </div>
{% endblock %}
```

### CMS page templates

Modify the `src/Pyz/Shared/Cms/Theme/default/templates/placeholders-title-content/placeholders-title-content.twig` and
`src/Pyz/Shared/Cms/Theme/default/templates/placeholders-title-content-slot/placeholders-title-content-slot.twig` files:
```twig
{% define data = {
    ...
} %}

{% block attributes %}
    {{ parent() }}
    {{ firstSpiritAttributes(data.idCmsPage, "content", "content", data.title, data.appLocale) }}
{% endblock %}

{# ... #}

{% block content %}
    {{ firstSpiritContent('sup_content') | raw }}

    {# ... #}

    {{ firstSpiritContent('sub_content') | raw }}

    <div class="box">
        {% cms_slot 'slt-8' with {
            idCmsPage: data.idCmsPage,
        } %}
    </div>
{% endblock %}
```


### Home page template
Modify the `src/Pyz/Yves/HomePage/Theme/default/views/home/home.twig` files:
```twig
{% extends template('page-layout-main') %}


{% block attributes %}
    {{ parent() }}
    {{ firstSpiritAttributes("homepage", "content", "homepage", data.title, data.appLocale) }}
{% endblock %}

    {# ... #}

            {% block content %}
                {{ firstSpiritContent('stage') | raw }}

                {% cms_slot 'slt-3' %}

                {{ firstSpiritContent('content') | raw }}

            {% endblock %}
        </main>
    </div>
{% endblock %}

```

### FirstSpirit component template
Create the file `src/Pyz/Shared/CmsBlock/Theme/default/template/fs_content_block.twig` with the following content:
```twig
{% define data = {
    fsData: fsData,
    template: template,
    templateModule: templateModule
} %}

{% block content %}
    {% include molecule( data.template, data.templateModule) with{
        data: {
            fsBlockData: data.fsData
        }
    }only %}
{% endblock %} 
```


## Testing
To test a particular branch in your Spryker installation replace _{branchname}_ in the command below:
```
$ docker/sdk cli composer require ecom-espirit/cfc-spryker-content:dev-{branchname}
```
