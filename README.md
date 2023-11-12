# cfc-spryker-content
This module is part of the FirstSpirit Connect for Commerce Spryker integration.
It is responsible to render the content maintained in FirstSpirit.

## Installation
Add the following to your `composer.json` file
```json
    "repositories": [
        {
            "url": "https://github.com/e-spirit/cfc-spryker-content.git",
            "type": "vcs"
        }
    ],
```
and run
```
$ composer require e-spirit/cfc-spryker-content
```
## Configuration
Add the following to your configuration:
```php
<?php

use Crownpeak\Shared\FirstSpiritContent\FirstSpiritContentConstants;
use Spryker\Shared\Kernel\KernelConstants;

// Allow the Twig template of our reference components to be used
$config[KernelConstants::PROJECT_NAMESPACES] = [
  'Pyz',
  'EcomExtra',
  'Crownpeak',
];

// Preview
$config[FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_WEB_HOST] = '<FS Host>';
$config[FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_AUTHENTICATION_TOKEN] = '<Token>';

// CFC Frontend API
$config[FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_SCRIPT_URL] = '<URL>';
$config[FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_SCRIPT_LOG_LEVEL] = '0';
$config[FirstSpiritContentConstants::FIRSTSPIRIT_FRONTEND_API_SERVER_URL] = '<URL>';
$config[FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_SCRIPT_BASE_URL] = '<URL>';

// General
$config[FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_RENDERED_TEMPLATE_CACHE_DURATION] = 0;
$config[FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_API_RESPONSE_CACHE_DURATION] = 0;
$config[FirstSpiritContentConstants::FIRSTSPIRIT_PREVIEW_DISPLAY_BLOCK_RENDER_ERRORS] = true;

// Content pages
$config[FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_URL_PREFIX] = 'content';
$config[FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING] = [
  'contentpage' => '@FirstSpiritUi/views/fs-content-page/fs-content-page.twig',
  'landingpage' => '@FirstSpiritUi/views/fs-content-page/fs-content-page.twig',
  FirstSpiritContentConstants::FIRSTSPIRIT_CONTENT_PAGE_TEMPLATE_MAPPING_ERROR => '@FirstSpiritUi/views/fs-error/fs-error.twig'
];


// Component mapping
$config[FirstSpiritContentConstants::FIRSTSPIRIT_SECTION_TEMPLATE_MAPPING] = [
  'text_image' => 'Crownpeak:FirstSpiritReferenceComponents/fs-text-image',
  'banner' => 'Crownpeak:FirstSpiritReferenceComponents/fs-banner',
  'carousel' => 'Crownpeak:FirstSpiritReferenceComponents/fs-carousel',
  'multi_slot_container' => 'Crownpeak:FirstSpiritReferenceComponents/fs-multi-slot-container',
  'interactive_image' => 'Crownpeak:FirstSpiritReferenceComponents/fs-interactive-image',
  'interactive_youtube_video' => 'Crownpeak:FirstSpiritReferenceComponents/fs-interactive-video',
  'teaser_grid' => 'Crownpeak:FirstSpiritReferenceComponents/fs-teaser-grid',
  '*' => 'Crownpeak:FirstSpiritReferenceComponents/fs-data-visualizer',
];

// Format mapping
$config[FirstSpiritContentConstants::FIRSTSPIRIT_DOM_EDITOR_TEMPLATE_MAPPING] = [
  // Links
  'dom_external_link' => 'Crownpeak:FirstSpiritContent/fs-link',
  'dom_content_link' => 'Crownpeak:FirstSpiritContent/fs-link',
  'dom_product_link' => 'Crownpeak:FirstSpiritContent/fs-link',
  'dom_category_link' => 'Crownpeak:FirstSpiritContent/fs-link',
  // Formats
  'bold' => 'Crownpeak:FirstSpiritContent/fs-format',
  'italic' => 'Crownpeak:FirstSpiritContent/fs-format',
  'subline' => 'Crownpeak:FirstSpiritContent/fs-format',
  'format.h2' => 'Crownpeak:FirstSpiritContent/fs-format',
  'format.h3' => 'Crownpeak:FirstSpiritContent/fs-format',
  'format.subline' => 'Crownpeak:FirstSpiritContent/fs-format',
];


```

## Registering components
### Add namespace in Yves EventDispatcherDependencyProvider

Add the following to your `src/Pyz/Yves/EventDispatcher/EventDispatcherDependencyProvider.php` file:
```php
use Crownpeak\Yves\FirstSpiritContent\Plugin\EventDispatcher\FirstSpiritContentEventDispatcherPlugin;

// ...

    protected function getEventDispatcherPlugins(): array
    {
        return [
            // ...
            new FirstSpiritContentEventDispatcherPlugin()
        ];
    }
```

### Add namespace in Yves TwigDependencyProvider

Add the following to your `src/Pyz/Yves/Twig/TwigDependencyProvider.php` file:

```php
// ...
use Crownpeak\Yves\FirstSpiritContent\Plugin\Twig\GlobalsTwigPlugin;
use Crownpeak\Yves\FirstSpiritContent\Plugin\Twig\AttributesTwigFunction;
use Crownpeak\Yves\FirstSpiritContent\Plugin\Twig\CategoryDataTwigFunction;
use Crownpeak\Yves\FirstSpiritContent\Plugin\Twig\ContentTwigFunction;
use Crownpeak\Yves\FirstSpiritContent\Plugin\Twig\LinkTwigFunction;
use Crownpeak\Yves\FirstSpiritContent\Plugin\Twig\ProductDataTwigFunction;


// ...

    protected function getTwigPlugins(): array
    {
        return [
            // ...
            new GlobalsTwigPlugin(),
            new ContentTwigFunction(),
            new AttributesTwigFunction(),
            new ProductDataTwigFunction(),
            new CategoryDataTwigFunction(),
            new LinkTwigFunction()
        ];
```

### Register router in Yves RouterDependencyProvider

Add the following to your `src/Pyz/Yves/Router/RouterDependencyProvider.php` file:

```php
// ...
use Crownpeak\Yves\FirstSpiritContent\Plugin\Route\CmsBlockRenderRoutePlugin;
use Crownpeak\Yves\FirstSpiritContent\Plugin\Route\ContentPagesRoutePlugin;


// ...

    protected function getRouteProvider(): array
    {
        return [
            // ...
            new CmsBlockRenderRoutePlugin(),
            new ContentPagesRoutePlugin()
        ];
```

## Extend Twig templates

### Main page layout
Modify the `src/Pyz/Yves/ShopUi/Theme/default/templates/page-layout-main/page-layout-main.twig` file:
```twig
{% extends template('page-layout-main', '@SprykerShop:ShopUi') %}

{% define data = {
	@@ -158,3 +162,10 @@
        {% include organism('notification-area') only %}
    {% endblock %}
{% endblock %}

{% block footerScripts %}
    {{ parent() }}
    {{ firstSpiritCfcScriptUrl|raw }}
{% endblock %}
```


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

### FirstSpirit driven content pages
Create the file `src/Pyz/Yves/FirstSpiritUi/Theme/default/views/fs-content-page/fs-content-page.twig` with the following content:
```twig
{% extends template('page-layout-main') %}

{% block attributes %}
    {{ parent() }}
    {{ firstSpiritAttributes(contentPageData.refId, "content", "content", title, data.appLocale) }}
{% endblock %}


{% block container %}
    <div class="container">
        <div class="container__inner">
            {% block breadcrumbs %}{% endblock %}
        </div>
    </div>

    <div class="container">
        <main class="container__inner">


            {% block title %}
                <h1 class="title title--main title--h2 title--medium spacing-y">{{ title }}</h1>
            {% endblock %}

            {% block content %}
                {{ firstSpiritContent('stage') | raw }}

                {{ firstSpiritContent('content') | raw }}
            {% endblock %}
        </main>
    </div>
{% endblock %}
```


### FirstSpirit error page
Create the file `src/Pyz/Yves/FirstSpiritUi/Theme/default/views/fs-error/fs-error.twig` with the following content:
```twig
{% extends template('page-layout-main') %}

{% block container %}
    <div class="container">
        <main class="container__inner">


            {% block title %}
                <h1 class="title title--main title--h2 title--medium spacing-y">{{ title }}</h1>
            {% endblock %}

            {% block content %}
                <p class="text-center">
                    {{ error }}
                </p>
            {% endblock %}
        </main>
    </div>
{% endblock %}
```


## Testing
To test a particular branch in your Spryker installation replace _{branchname}_ in the command below:
```
$ docker/sdk cli composer require e-spirit/cfc-spryker-content:dev-{branchname}
```
