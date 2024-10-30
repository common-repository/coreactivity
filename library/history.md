# Dev4Press Library

## Changelog

### Version 4.9.2 - 2024.06.26

* Edit: expanded list of detected email types to include coreSecurity Pro
* Edit: various styling improvements to all the grid pages
* Edit: improvements to the `Features` loading for the main site only features
* Fix: few minor issues with the handling of the feature settings
* Fix: minor problems with the standard set of `KSES` tags and attributes

### Version 4.9.1 - 2024.06.12

* Edit: few tweaks and improvements to the license code validation
* Fix: license code validation shows wrong information in some cases

### Version 4.9 - 2024.06.03

* New: default styling for the `Micromodal` JS modal library
* New: added several more icons to the icon font
* Edit: additional optional settings for some of the option types
* Removed: use of `EOT` font from the icon font stylesheets
* Removed: all the previously included `MO` translation files
* Fix: several issues when saving plugin boolean settings
* Fix: an expandable pair option can skip new values on saving

### Version 4.8 - 2024.04.26

* New: `Core` object has priority options for the loading actions
* New: `Settings` object can specify some groups as network wide
* New: settings now can render the `Clear` button for text-based field
* New: checkbox based options can be used for the option switch control
* New: check for capability for postback for main plugin panels
* New: rewritten most of the settings processing for better validation
* New: `Sanitize` class has new method for `deep` processing
* New: `Sanitize` class renames some methods with proper names
* New: added several more UI icons to the icon font
* Edit: improvements to the default styling for dashboard and tabs
* Edit: improvements to base `Panel` object content file names
* Edit: improvements to the `License` base class related to API updates
* Edit: improvements to the autoload function implementation
* Edit: improvements to import of plugin settings
* Edit: various improvements to the data processing and saving
* Edit: outdated actions prefixes replacing `d4p` with `dev4press`
* Edit: various improvements to sanitization and escaping
* Edit: built-in cacert.pem 20240311
* Deprecated: several methods in the `Sanitize` class
* Fix: some `Core` properties were private not protected
* Fix: license check job initialized with filter not action

### Version 4.7.3 - 2024.03.21

* Fix: problem with the Table object and orderby processing

### Version 4.7.2 - 2024.03.15

* Edit: few more updates to the options rendering class
* Fix: several more instances for missing sanitation in the options rendering

### Version 4.7.1 - 2024.03.04

* Edit: `IP` class option for forwarded IP keys

### Version 4.7 - 2024.02.12

* New: option for expandable pairs has new layout and styling
* New: expanded the `Elements` method `input` with more attributes
* New: expanded the `BBP` class with `can_use_pretty_urls` method
* New: expanded the `BBP` class with new roles check methods for `user_id`
* New: expanded the `WPR` class with new roles check methods for `user_id`
* New: expanded the `File` class with `put_contents` method
* New: added several more UI icons
* Edit: rewritten `IP` class for getting visitor IP with extra options
* Edit: various improvements to the metabox handling and styling
* Edit: additional filter for the multisite menu integration for blogs
* Edit: improvements to the admin page header navigation styling
* Edit: settings `Render` class uses `Elements` for input types
* Edit: various improvements for expandable pairs control
* Edit: changes related to WordPress and PHP code standards
* Edit: built-in cacert.pem 20231212
* Removed: all previously deprecated methods
* Fix: problem with the rendering of hierarchy checkboxes
* Fix: missing default elements for expandable pairs control
* Fix: several issues with validation of data for `Widget` class
* Fix: issue with the action URL method for admin class

### Version 4.6 - 2023.12.28

* New: metabox styling rebuilt and improved with sidebar styling support
* Edit: show knowledge base button/link for individual features
* Edit: various improvements to the `Sanitize` class
* Edit: expanded the `KSES` validation for the `Input` control
* Fix: few issues with rendering of the widgets tab control
* Fix: method for basic cleanup in `Sanitize` class fails in some cases
* Fix: input rendering class stripping `min` and `max` attributes
* Fix: checkbox and radio rendering not matching selected value

### Version 4.5.2 - 2023.12.19

* Edit: function `json_encode` replaced with `wp_json_encode`
* Edit: improved `IP` class method for getting visitor IP
* Edit: various validation changes and function use changes
* Fix: potential vulnerability related to visitor IP method

### Version 4.5.1 - 2023.12.18

* Edit: few minor tweaks in the Customizer `Manager` class
* Fix: few escaping issues with rendering some settings

### Version 4.5 - 2023.12.12

* New: licensing support for settings and validation
* New: `Enqueue` classes prefixes now match the library version
* New: `Background` abstract class for handling background jobs
* New: `AJAX` and `CRON` expanded classes for the `Background` class
* New: settings elements related to the license value
* New: admin interface group box with support for tabs
* Edit: several improvements to the main `Wizard` class and styling
* Edit: fully rewritten cleanup method for `HTACCESS` class
* Edit: big styling changes and improvements for the plugin interface
* Edit: various changes to several plugin core classes
* Edit: improvements to the admin header display with extra buttons
* Edit: improvements to the `Features` panel filtering
* Edit: unique library prefix for files added by `Enqueue`
* Fix: issue with the screen options handling on networks setups
* Fix: minor problem with the getting group settings

### Version 4.4 - 2023.11.06

* New: render Changelog and History About panels from markdown
* New: email detection support for WooCommerce standard emails
* New: email detection support for WooCommerce `WP_Email` class
* New: method in `Str` to split camelcase string to word
* New: included PHP library `ParsedownExtra`
* New: default styling for the `DL` controls
* New: added several more UI icons
* Edit: several updates and improvements to `DateTime` class
* Edit: updated email detection for new WordPress emails
* Edit: library changelog files switched to the Markdown format
* Edit: many styling changes to the default `Tabs` control
* Edit: many small styling tweaks for the dashboard controls
* Fix: group select rendering problem with the selected value

### Version 4.3.5 - 2023.10.05

* Edit: minor changes to the `DBLite` handling of the logged query

### Version 4.3.4 - 2023.10.02

* New: class `File` has method to wrap `WP_Filesystem` use
* Edit: settings import from file now uses WordPress native functions

### Version 4.3.3 - 2023.09.26

* Edit: more changes related to PHPCS and WPCS validation

### Version 4.3.2 - 2023.09.25

* New: added several more UI icons

### Version 4.3.1 - 2023.09.20

* Edit: more changes related to PHPCS and WPCS validation

### Version 4.3 - 2023.09.05

* New: full validation with the PHPCS and additional WPCS rules
* New: some forms panel split into the elements files for re-usability
* New: settings elements can have toggleable additional information
* New: settings elements can have buttons
* New: class `Wizard` for setting up Setup Wizard panels
* New: class `Help` for setting up admin side context help
* New: class `Settings` direct preload of the multisite network settings
* New: class `HTAccess` expanded with the method to check .HTACCESS status
* New: class `Elements` expanded with new rendering methods
* New: helper `Data` class to hold some common data and lists
* New: many smaller new methods and functions added through the library
* New: added several more UI icons
* Edit: improvements to the `Panel` class URLs and forms loading
* Edit: improvements to the `DBLite` class by using magic `__call` method
* Edit: improvements to the Geolocation process and classes
* Edit: improvements to the coding standards and formatting
* Edit: improvements to the default Help panels content
* Edit: massive improvements to Features in relation to network mode plugins
* Edit: many updates to the plugin settings panel rendering
* Edit: many styling improvements to the various parts of the interface
* Edit: improvements to the rendering of the settings controls
* Edit: few improvements to the plugins `Store` class
* Edit: class `HTAccess` improves the writing to the file
* Edit: class for `PostBack` can handle network and blog mode plugins
* Edit: optimization of the font generating file sources
* Edit: updates to the translations and strings included
* Edit: several more strings escaping and kses-ing for display
* Updated: JS Cookie 3.0.5
* Updated: built-in cacert.pem 20230822
* Removed: clipboard JS file since it is already in WordPress core
* Removed: standalone print and debug functions as no longer used
* Removed: class `Debug` as it is now obsolete
* Removed: all previously deprecated methods
* Fix: several more instances for missing sanitation in the options rendering
* Fix: header navigation in multisite mode show items that don't belong

### Version 4.2 - 2023.06.16

* New: complete the network based `Menu` class
* New: added `KSES` class for processing HTML strings for display
* New: added `Detection` class to detect email sender and source
* New: added `Source` helper class to detect file source
* New: added plugin reference and the icon
* New: added several more UI icons
* New: added `Elements` method to render grouped checkboxes
* New: added settings type for the grouped checkboxes
* New: Database install class `InstallDB` has the version property
* New: updated and modernized flags library
* New: expanded the main `WordPress` class
* New: rewritten GEOIP handling objects
* Edit: improvements to the `DBLite` class
* Edit: improvements to the `Scope` class
* Edit: improvements to the `Panel` class
* Edit: improvements to the `Table` class
* Edit: improvements to the `Table` default styling
* Edit: improvements to database installation process
* Edit: improvements to the admin core objects
* Edit: many small tweaks and settings changes for admin panels
* Edit: many tweaks and updates to the admin styling
* Edit: improvements to the admin responsiveness
* Edit: improvements to the `Elements` rendering
* Edit: improved support for the network plugins
* Edit: several more strings escaping for display
* Removed: old flags collection CSS and images
* Fix: server IP warning when in CLI mode
* Fix: potential issue with the grouped select render method

### Version 4.1.1 - 2023.05.15

* New: added `exclamation` icon
* New: few additional styling changes
* Fix: wrong `question` icon used
* Fix: issue with the `Table` referer use

### Version 4.1 - 2023.05.04

* New: `Sanitize` includes wrapper for the URL
* New: Rewritten JavaScript and CSS for the Meta boxes
* New: Expanded collection of UI icons
* New: JavaScript library for QRCodes: Kjua-SVG 1.13.1
* New: Admin panel handling the cards content layout better
* New: Main admin object handles screen options saving
* Updated: Improvements to About panel responsive styling
* Updated: Improvements to the `Table` base class
* Updated: Various improvements to the PHP code
* Updated: CSS pack no longer contains Balloon styling
* Fix: issues with screen load panel initialization

### Version 4.0.1 - 2023.03.28

* Updated: Few changes to the `plugin_locale` filter usage
* Fix: issue with the `plugin_locale` filter missing domain

### Version 4.0 - 2023.03.28

* New: Expanded collection of UI icons
* New: Replaced most of the plugin icons
* New: Base abstract `Store` class for data storage
* New: `IP` class mostly rewritten and expanded
* New: Connection between admin and core plugin classes
* New: Plugin `Core` with abstract method returning `Features` object
* New: Migration guide file with the class name changes
* Updated: Namespace changes for some classes
* Updated: `IP` class expanded Cloudflare IP range
* Updated: Show knowledge base link for panels if available
* Updated: `DBLite` readability of the `build_query` method
* Updated: Few updates to the settings rendering
* Updated: Various improvements to the styling
* Updated: Recommendation API list of plugins
* Updated: built-in cacert.pem 20230110
* Deprecated: `DBLite` methods for time zone offset
* Removed: All previously deprecated methods
* Fix: `DBLite` method `analyze_table` was not returning results
* Fix: Few issues with the `IP` class range methods
* Fix: Icons missing from the `Icons` class
* Fix: Several minor styling issues
* Fix: Referencing the namespaces that are not used
* Fix: Features sidebar references a plugin

### Version 3.9.3 - 2023.02.13

* Updated: Abstract Table class now has a db() method
* Updated: DBLite class prepare_in_list expanded with empty check
* Fix: Abstract Table class with hardcoded DB calls wrapper

### Version 3.9.2 - 2023.02.03

* Fix: Potential conflict causing issue with the logging of queries

### Version 3.9.1 - 2023.01.26

* Updated: JavaScript replacing `substr` with `substring`

### Version 3.9 - 2023.01.16

* New: Features base classes
* New: Completed Features base system and styling
* New: Features panel with filtering and search
* New: Instance methods for all abstract classes
* New: Many new features related to the interface
* New: Many methods added to BBP Quick class
* New: $_GET shortcut methods for Sanitize class
* New: System requirements: PHP 7.3 or newer
* New: System requirements: Tested with PHP 8.1 and 8.2
* Updated: Various improvements to the admin styling
* Updated: Improvements to the Settings class
* Updated: Improvements to the Table class
* Updated: Improvements to the Widgets class
* Updated: Various improvements to Core classes
* Updated: Expanded BBPress static Core class
* Updated: Additional icons to the Icons font
* Updated: built-in cacert.pem 20221011
* Updated: Clipboard.js 2.0.11
* Removed: All previously deprecated functions
* Deprecated: Some additional functions and methods
* Fix: Problem with using capabilities in Widget class
* Fix: Problem with storing capabilities in Widget class

### Version 3.8 - 2022.05.14

* New: Base Panel object now builds the classes
* New: Base Micromodal object to creating modal dialogs markup
* New: Base Grid Table object and SCSS/CSS with styling
* New: Admin object flag to skip update/install panels
* New: Settings for CSS Size can allow some units only
* New: Core object initializes DateTime by default
* New: Micromodal JS library 0.4.10
* Updated: Method to detect the bbPress activity
* Updated: Method to detect the BuddyPress activity
* Updated: Expanded class for AJAX handling
* Updated: Some styling elements for the plugin dashboard
* Updated: Various minor updates to the Panel class
* Updated: Post Type and Taxonomy traits updated labels
* Updated: Each menu item can have own capability
* Updated: Sanitize method names updates
* Updated: built-in cacert.pem 20220426
* Updated: Mark.js 9.0.0
* Updated: Clipboard.js 2.0.10
* Updated: Flatpickr 4.6.13
* Fix: REST API request detection in WordPress object
* Fix: Loading of sub-templates in some cases
* Fix: curly braces access for array in one instance

### Version 3.7.4 - 2022.03.11

* Updated: Several minor improvements and tweaks

### Version 3.7.3 - 2022.03.02

* Updated: Rendering of the settings datetime field
* Updated: Additional sanitation calls for settings fields
* Updated: Many improvements to the Getback class
* Updated: Various minor display and styling tweaks
* Removed: Alpha Color Library
* Fixed: Minor issue with the Enqueue method generated path
* Fixed: Few minor issues in the JavaScript
* Fixed: Few missing escape on echo calls
* Fixed: Some minor rendering issues
* Fixed: Wrong argument names for some settings
* Fixed: Wrong email sanitation call

### Version 3.7.2 - 2022.02.20

* New: Main Admin class method to generate getback URL
* Updated: Scope class support for WP CLI
* Updated: DBLite magic properties list
* Updated: Information class default requirements

### Version 3.7.1 - 2022.02.01

* New: Added few new icons
* Updated: Some additional visual improvements
* Updated: Removed obsolete CSS properties

### Version 3.7 - 2021.11.02

* New: Static classes to replace namespaced functions
* New: Big redesign for the admin side interface
* New: Now uses CSS variables as a base for styling
* New: Basis for styling layouts is the flexbox
* New: Flexbox based core grid styling implementation
* New: Pattern validation for slug field types
* Updated: Many improvements and tweaks to the About panel
* Updated: Improvements to the RTL styling
* Updated: built-in cacert.pem 20210930
* Updated: Clipboard 2.0.8
* Updated: Cookie 3.0.1
* Updated: MarkJS 9.0.0
* Updated: WP Color Picker Alpha 3.0.0
* Removed: Various unused JavaScript libraries

### Version 3.6 - 2021.08.14

* New: Core Library class
* New: Core WordPress class
* New: Core Resources class
* New: Added 10+ new icons
* New: Many new functions
* New: Improved library loading and initialization
* New: Improved admin Plugin and Enqueue handling
* New: Many changes to various core classes
* New: About panel with System tab
* New: WordPress 5.8 compatibility
* Updated: Minor updates to some functions
* Updated: Many updates to the admin styling
* Updated: Removed retired plugins from the list
* Updated: JavaScript migrate compatibility updates
* Updated: Various PHP related improvements
* Updated: built-in cacert.pem 20210705
* Removed: Previously deprecated functions
* Removed: Many defines for system flags
* Fixed: mysqli method for DBLite class
* Fixed: various minor styling issues

### Version 3.5.2 - 2021.06.21

* New: bbPress related functions
* Updated: Various core classes PHP specifications
* Updated: Many small tweaks and changes
* Updated: Flatpickr 4.6.9
* Fixed: Some widget related issues

### Version 3.5.1 - 2021.05.01

* New: Icon and information about ArchivesPress plugin
* Updated: Minor improvements to the ObjectSort class
* Fixed: Few issues related to sanitize functions scope

### Version 3.5 - 2021.04.14

* New: Major refactoring to include version for every namespace
* New: Major refactoring to add namespaces to functions
* New: Reorganization of functions and function files
* New: Elements class to handle rendering of common HTML tags
* New: Base Widget class supports generating shortcode
* New: Admin panels support for RTL text orientation
* New: Options core Element support for switch section visibility
* Updated: Widget interface improvements and few tweaks
* Updated: Frontend Enqueue change action priorities
* Updated: Improvements to main icons' font organization and size
* Updated: Many small tweaks and changes
* Updated: Many styling improvements
* Removed: HTML tags rendering functions
* Removed: IP wrapper functions
* Removed: Many obsolete function
* Fixed: Issues with the names of some CSS files
* Fixed: Detection of the RTL text orientation
* Fixed: Various small issues

### Version 3.4.1 - 2021.02.24

* Fixed: Issue with the checkboxes rendering function

### Version 3.4 - 2021.02.15

* New: Options core Element support for switch control setting
* Updated: Various styling improvements to the metabox rendering
* Updated: built-in cacert.pem 20210119
* Updated: Licensing text for individual files

### Version 3.3.1 - 2020.12.28

* New: CSS Size based settings
* Updated: Various minor core improvements
* Fixed: Few issues with the admin side rendering

### Version 3.3 - 2020.11.09

* New: Options core classes support render and process overrides
* New: Date and time based settings
* New: Numeric range based settings
* New: Pages and categories dropdown settings
* New: Functions to sanitize date and time inputs
* New: Improvements to the text generator classes
* New: All new plugin and addons icons
* New: Font with icons using embed Woff and Woff2 fonts
* Updated: Improved media classes content and organization
* Updated: Font expanded with new UI icons
* Updated: Various rendering tweaks and improvements
* Updated: Various styling improvements
* Updated: built-in cacert.pem 20201014
* Updated: Flatpickr 4.6.6
* Fixed: Few issues with the settings rendering

### Version 3.2 - 2020.08.10

* New: Regex validation functions
* New: Icons handling class
* Updated: Improvements to icons font
* Updated: Many core classes improvements
* Updated: Various JavaScript improvements
* Updated: Various minor improvements
* Updated: Function to format file size
* Updated: built-in cacert.pem 20200722
* Fixed: Settings import minor issues

### Version 3.1.3 - 2020.06.30

* New: Network admin Plugin class
* New: Animated Popup library

### Version 3.1.2 - 2020.06.22

* New: Settings and Tools panels balloon tips
* Updated: Font expanded with new UI icons
* Updated: Various core classes improvements
* Fixed: few minor issues with Widget core class
* Fixed: some messages not displayed properly
* Fixed: missing deactivate plugin function
* Fixed: showing about translation when there are none available

### Version 3.1.1 - 2020.06.17

* Updated: Dashboard elements styling

### Version 3.1 - 2020.05.25

* New: Frontend shared Enqueue class
* New: Balloon CSS file for tooltips
* New: Main CSS 'pack' used on plugin pages
* Updated: Expanded information about the import tool
* Updated: Improved Export and Import
* Fix: Minor issues with Admin Enqueue

### Version 3.0.1 - 2020.04.22

* New: WordPress content Traits
* New: Expanded media library uploads
* New: Flatpickr library
* Updated: Enqueue improvements
* Updated: Various improvements
* Updated: About Dev4Press page
* Updated: Code cleanup and reformat
* Fix: Minor admin panel issues
* Fix: Minor widgets class issues

### Version 3.0 - 2020.03.16

* New: Fully Rewritten Library

### Version 4.8 - 2024.04.26

* New: `Core` object has priority options for the loading actions
* New: `Settings` object can specify some groups as network wide
* New: settings now can render the `Clear` button for text-based field
* New: checkbox based options can be used for the option switch control
* New: check for capability for postback for main plugin panels
* New: rewritten most of the settings processing for better validation
* New: `Sanitize` class has new method for `deep` processing
* New: `Sanitize` class renames some methods with proper names
* New: added several more UI icons to the icon font
* Edit: improvements to the default styling for dashboard and tabs
* Edit: improvements to base `Panel` object content file names
* Edit: improvements to the `License` base class related to API updates
* Edit: improvements to the autoload function implementation
* Edit: improvements to import of plugin settings
* Edit: various improvements to the data processing and saving
* Edit: outdated actions prefixes replacing `d4p` with `dev4press`
* Edit: various improvements to sanitization and escaping
* Edit: built-in cacert.pem 20240311
* Deprecated: several methods in the `Sanitize` class
* Fix: some `Core` properties were private not protected
* Fix: license check job initialized with filter not action
