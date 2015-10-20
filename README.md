# yamm-nav-walker
A custom WordPress nav walker class to implement the Yamm 3 bootstrap mega menu navigation style in a custom theme using the WordPress built in menu manager.

##NOTE

This is a utility class that is intended to format your WordPress theme menu with the correct syntax and classes to utilize the Bootstrap dropdown navigation, and does not include the required Bootstrap JS files or Yamm 3 files. You will have to include them manually.

- http://getbootstrap.com/getting-started/#download
- http://geedmo.github.io/yamm3/

## Installation

Place yamm-nav-walker.php in your WordPress theme folder /wp-content/your-theme/

Open your WordPress themes functions.php file /wp-content/your-theme/functions.php and add the following code:
```php
// Register Custom Navigation Walker
require_once('yamm-nav-walker.php');
```

## Usage

Update your `wp_nav_menu()` function in `header.php` to use the new walker by adding a "walker" item to the wp_nav_menu array.

```php
<?php
    wp_nav_menu( array(
        'menu'              => 'primary',
        'theme_location'    => 'primary',
        'depth'             => 4,
        'container'         => 'div',
        'container_class'   => 'collapse navbar-collapse',
        'container_id'      => 'bs-example-navbar-collapse-1',
        'menu_class'        => 'nav navbar-nav yamm',
        'fallback_cb'       => 'Yamm_Nav_Walker_menu_fallback',
        'walker'            => new Yamm_Nav_Walker())
    );
?>
```

Your menu will now be formatted with the correct syntax and classes to implement Bootstrap dropdown navigation. 

You will also want to declare your new menu in your `functions.php` file.

```php
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'THEMENAME' ),
) );
```

Typically the menu is wrapped with additional markup, here is an example of a `navbar-fixed-top` menu that collapse for responsive navigation.

```php
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo home_url(); ?>">
                <?php bloginfo('name'); ?>
            </a>
    </div>

        <?php
            wp_nav_menu( array(
                'menu'              => 'primary',
                'theme_location'    => 'primary',
                'depth'             => 2,
                'container'         => 'div',
                'container_class'   => 'collapse navbar-collapse',
		        'container_id'      => 'bs-example-navbar-collapse-1',
                'menu_class'        => 'nav navbar-nav',
                'fallback_cb'       => 'Yamm_Nav_Walker_menu_fallback',
                'walker'            => new Yamm_Nav_Walker())
            );
        ?>
    </div>
</nav>
```

To change your menu style add Bootstrap nav class names to the `menu_class` declaration.

Review options in the Bootstrap docs for more information on nav classes
http://getbootstrap.com/components/#nav

You can also use the full width walker like so:

```php
<?php
    wp_nav_menu( array(
        'menu'              => 'primary',
        'theme_location'    => 'primary',
        'depth'             => 4,
        'container'         => 'div',
        'container_class'   => 'collapse navbar-collapse',
        'container_id'      => 'bs-example-navbar-collapse-1',
        'menu_class'        => 'nav navbar-nav yamm',
        'fallback_cb'       => 'Yamm_Fw_Nav_Walker_menu_fallback',
        'walker'            => new Yamm_Fw_Nav_Walker())
    );
?>
```