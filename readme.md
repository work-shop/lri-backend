# Spin Backend Template

This is Work-Shop's backend development template. Any new development project in the `spin` model should be based off this template. At the end of each project, any work that you've done that's contributed to the general performance of this template should be assessed with the group and reintegrated into this repository for future use. It's useful to look at your work on the backend as potentially contributing to this project, if you can.

`spin-backend` serve as the CMS's for our client's sites. As a rule, these are built on Wordpress. This repository is a version-controlled `wp-content` directory. All of wordpress' library utilities sit in directories "above" this one in your specific project. This directory contains the boilerplate configuration code and standard array of plugins you'll need for the your CMS to function properly with `spin-frontend`.

This repository, along with the local (or remote) `wp-config.php` file generated for your project by `spin-core` should be enough for you get a local (or remote) version of this project running.

*The following items are some points of interest in this repository, which are useful points of customization.*

### `Class_Init_Actions.php`
Most of the customization of an individual CMS will happen in this class. This class serves as a point of implementation for binding critical theme functions and behaviors to the appropriate hooks. Domains covered are:

- **Custom Post Type Registration**. All custom post types are registered in this class. When registering a class, it's important to include a `rest_base` value, which defines the registered endpoint for the post type's contents, as well as a `rest_controller class`. The default here is `WP_REST_Posts_Controller`, but you have an option to define custom backend behaviors by specifying other controller classes. The required interface for a Rest Controller is defined in source [here](https://developer.wordpress.org/reference/classes/wp_rest_controller/).
- **Custom Taxonomy Registration**. The class handles registration of custom taxonomies similarly.
- **Options Page Registration**. Options pages are units that can be populated using [Advanced Custom Fields](https://www.advancedcustomfields.com/pro/). Any configuration or global settings defined via options pages are recoverable via the `options` endpoint on the API, which is nicely centralized and simplifies access to global resources on `spin-frontend`.
- **Featured Image Size Settings**. Hooks are provided for recording desired images sizes and crops.
- **All Settings**. We've included a setting that shows all of the  database options fields on a single page, for debugging.
- **Backend CSS**. Backend css is enqueued in the file, and can be modified in this repository, if desired.
