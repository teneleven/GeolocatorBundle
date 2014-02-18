Teneleven Geolocator Bundle
===========================

This bundle adds the ability to make your models geo-searchable and displayed on a map.
Example uses are store locators, apartment finders, nearest bus stops.. any geo-searchable scenario you can dream up.

![Example](https://f.cloud.github.com/assets/624921/2199113/430a8236-98d1-11e3-8c9e-1e4aad3eacee.JPG)

Out of the box the bundle works with Doctrine ORM, but support for other persistence layers is trivial to implement.

To keep the bundle lean and mean we leverage other great bundles where possible, such as:

 * [BazingaGeocoderBundle](http://github.com/willdurand/Geocoder) for the actual geo-coding
 * [IvoryGoogleMapBundle](https://github.com/egeloen/IvoryGoogleMapBundle) for elegant google map handling
 * [CraueGeoBundle](https://raw2.github.com/craue/CraueGeoBundle) for Doctrine geo distance calculations

Sensitive defaults are automatically prepended but you are encouraged to tap into each bundle's configuration directly.

## Installation & Setup

### Download the bundle via [composer](http://getcomposer.org)

Require the bundle in your composer.json file:

```json
{
    "require": {
        "teneleven/geolocator-bundle": "*"
    }
}
```

or run:

```sh
php composer.phar require teneleven/geolocator-bundle:*
```

in a shell.

### Enable the bundle

```php
// in app/AppKernel.php
public function registerBundles() {
	$bundles = array(
		// ...
		new Teneleven\Bundle\Geolocator\TenelevenGeolocatorBundle(),
	);
	// ...
}
```

### Configure your models

Each model type you want to support requires a configuration entry under a unique alias. This alias is used in routing configuration and command parameters.

```yml
# in app/config/config.yml
teneleven_geolocator:
    locatables:
        dealer: #this is the alias for your model - name this anything you like
            repository: teneleven_sandbox.repository.dealer #this is the service ID of the repository for this model
            radius: 300 #(optional) default limit for search area (in miles)
            limit: 50 #(optional) limit the number of results
            address_properties: [street, city, state, zip] #these are properties of your model which together make up a geo-coder-friendly address

        #other models configs...
```

### Configure your routes

Below is an example route for the model configured above. It leverages the default controller which comes with the bundle and does not specify a custom template.

```yml
teneleven_sandbox_dealers:
    path:  /dealers
    defaults: { _controller: teneleven.geolocator.controller:locate, entity: dealer, template: ~ }
```

This routing configuration allows you to expose differently configured locators at different URLs, each with a customized location provider and a custom template, if you so desire.

### Geo-code your models

To be locatable via this bundle your model classes must implement ```Teneleven\Bundle\GeolocatorBundle\Model\GeolocatableInterface```.

Given the above configuration you can now run:

```sh
php app/console teneleven:geolocator:geocode your-model-alias1 your-model-alias2...
```

to apply geo-coordinates to each model instance. By default this bundle uses the Geocoder Google Maps adapter, but you can configure the Geocoder Bundle either way you like.
The ```your-model-alias``` argument is optional. If you leave it off, all models configured with this bundle will be geo-coded.

### You're done

Your models can now be geo-searched and should appear on the result page. Fire off a search and enjoy the fruits of your labor!
Did you know - clicking on a result will highlight the related marker, and clicking on a marker will highlight the related result.. how cool is that?!

## Customizing this bundle

This bundle is designed to be very flexible and make customizations easy. If you run into a use-case not described here, please open a ticket.

### Customizing the results template

The default template in this bundle includes several blocks to easily customize the following areas:

 * result list & individual result entries
 * popup-window content associated with map markers
 * the search form
 * the content area above the map

Simply extend this template in your project, override the desired blocks, and specify your new template in the routing configuration (see above).
Your template can also specify a layout to be used by setting a ```teneleven_geolocator_layout``` variable inside the template.

### Customizing the location provider

The location provider houses query logic and handles the search form.
If your model search includes custom parameters (other than the search location) you will need to extend the default logic and specify a custom search form.
One example use-case would be an apartment-finder which allows filtering and sorting by rent level, in addition to geo-distance.

### Customizing the controller

Currently the controller is where the map is being built. If you need to customize this functionality for some reason, you can provide your own controller implementation and specify it in the routing configuration.

## Commands & Address Extractors

The bundle includes one command, ```teneleven:geolocator:geocode```, to apply geo-coordinates to your models.
By default only models with empty latitude/longitude are processed, but you can customize this by providing your own location provider implementation.
You might, for example, want to refresh geo-coordinates on recently updated models, or ones that have NOT been updated in a while. Up to you.

Internally, geo-coordinates are provided by the great [Geocoder](https://github.com/willdurand/Geocoder) library, which requires an address input as string,
so we provide an ```AddressExtractorInterface``` along with a default implementation based on the [PropertyAccess](https://github.com/symfony/PropertyAccess) component.
If your address extraction logic goes beyond gluing together model properties, you are free to provide your own implementation.

## Tips & Tricks

 * Geocoder throttling - to comply with the terms of geo-coding services, you may want to use a Guzzle adapter with throttling plugin enabled
 * Map default settings are configurable via [IvoryGoogleMapBundle configuration](https://github.com/egeloen/IvoryGoogleMapBundle/blob/master/Resources/doc/usage/map.md).
   For Example, you can set a nice default center/zoom to point at your office:
   ```
   ivory_google_map:
       map:
           center:
               latitude:  32.726104
               longitude: -117.226081
               no_wrap: true

           zoom: 8
   ```

## Contributing

We welcome ideas, bug reports, and pull requests. Please submit tickets on this GitHub repository.
