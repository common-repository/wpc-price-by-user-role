=== WPC Price by User Role for WooCommerce ===
Contributors: wpclever
Donate link: https://wpclever.net
Tags: woocommerce, wpc, role price, user role, user roles
Requires at least: 4.0
Tested up to: 6.7
Version: 2.1.7
Stable tag: 2.1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WPC Price by User Role helps you configure discounts and adjust prices in bulk based on user roles.

== Description ==

Here comes the brand new **WPC Price by User Role for WooCommerce** plugin, which is bound to be one great tool for online stores that have multi-vendors, users of various user roles such as shop managers, wholesalers, retailers, customers, subscribers, etc. It is the most convenient way to configure discounts and adjust prices in bulk based on user roles.

The user-friendly interface with clean-coded features are made ready for site owners to configure prices and discounts for different user groups based on roles. Users can set up pricing rules for all products at once (storewide), or at the product basis (Premium). Prices can also be hidden in specific user roles. Unauthorized users can be required to log in to see prices as well.

There are two methods for configuring a new price: entering a number as the new fixed price or using a percentage of the original price. The percentage new price is recommended for Global prices as it can be applied dynamically on products throughout the store.

= Key Features =

- Adjust the General’s tab price for different user groups
- Display the full price format: Regular & Sale prices
- Hide prices and ATC button of products based on specific roles
- Require guest login to see product prices
- Highly flexible: multiple input types with a number or percentage for prices
- Set up a fixed new price as Regular or Sale price or both
- Set up a dynamic new price using a percentage of the original price
- Leave blank or use 100% to keep the original prices
- Pricing rules at two levels: Global (storewide) or Individual Products (Premium)
- Easy to use for different user roles: administrator, shop manager, customers, etc.

= User Roles =

**WPC Price by User Role** provides a full list of major roles on a site for site administrators to assign pricing rules on different levels of privileges.

In case you would like to raise the security and require more engagement from users, you can also hide prices and require guests to log in to see the prices. The Add to Cart button will be disabled for unauthorized guests as well. It’s also possible to hide the prices in specific user roles.

You can review and adjust the roles of users from the dashboard by navigating to the Users section >> All users.

= Price Adjustment =

By default, WooCommerce requires every product to have a price entered in the General tab to be available and purchasable. This plugin makes it possible to flexibly change that price for multiple products at a time or in specific items to suit the purchasing privileges of customers at various levels.

For example:

- Administrators: see full prices for management
- Shop managers/ wholesalers: enjoy 3% discount on all products
- Contributors: get a flat discount price of $29.00 for new arrival collection
- Customers: enjoy 2-5% on some products

Let’s take a product as an example with a price of $19.00 entered as its Regular price in the General tab.

In order to keep using the full price of products, leaving all boxes blank or entering 100% will result in no changes in the pricing. The frontend price of that item will be “$19.00” only.

**Global Prices vs Individual Prices**

For wholesalers who can enjoy 3% off all products, just navigate to the **WPClever menu >> Price by User Role**, choose a role from the dropdown then click on Set up for role for the boxes to appear. The pricing rules configured in this menu are global pricings for all users within the assigned roles and for all products across the stores.

In order to set up pricing rules for individual products, users must purchase the Premium plugin then open the Product Data section >> open the Price by User Role tab >> choose Override and repeat the same process above.

**Regular Price**

In all cases, the Regular price must always be higher than the Sale price. Despite that, the new Regular price can be higher than the current price ($19) in case of price increase in product as a result of price adjustment.

So, the new Regular price can be any number higher or lower than the current price $19 of that item: entering “150%”, “40”, “20%”,… is possible. But the new Sale price must be lower than the new Regular price: entering “140%”, “39”, “19%”,… for the Sale price is possible in the same product.

What we can see from this is, this plugin isn’t just about giving discounts, it’s about price adjustment – increase or decrease to a new fixed price or by a percentage of the original price.

**Sale Price**

To give a 3% discount, enter 97% on the Sale price box. The frontend price will have this format: “$19.00 $18.43”. If you enter “97%” in the Regular price box, this won’t be considered a discount so the discount price format won’t be applied.

To adjust the regular price for shop managers to see the official price of that product ($25) when it is launched for sale, fill on the Regular price box with the number “25” to set a new regular price. To let them enjoy a 3% discount off the price, enter “97%” in the Sale price box. The frontend price will be: “$25.00 $24.25”.

To give a flat new price, just enter a number in the corresponding box. If you consider the new price as a sale price of that product and put “29” in the Sale price box, the frontend price will be “$29.00” only. No discount price format is applied here as 29 is a higher price than the original price of that product ($19). The right way is to adjust the regular price to be higher than $29 at the same time, then enter “29” for the sale price, for example, Regular price-$30 & Sale price-$29. This will do the trick.

= WPC Plugins in Combination =

While checking out the price configurations, it would help users save a great deal of time using our [WPC Shop as a Customer for WooCommerce](https://wordpress.org/plugins/wpc-shop-as-customer/) plugin to switch between different user roles in just one click. Changing back and forth is easier to see the price adjustments without the need to fill out the username and password for different accounts to test things out. Give it a try.

== Installation ==

1. Please make sure that you installed WooCommerce
2. Go to plugins in your dashboard and select "Add New"
3. Search for "WPC Price by User Role", Install & Activate it
4. Go to WP-admin > WPClever > Price by User Role to set global prices
5. When adding/editing the product you can choose the Price by User Role tab then add prices as you want

== Changelog ==

= 2.1.7 =
* Updated: Support export/import products data

= 2.1.6 =
* Updated: Optimized the code

= 2.1.5 =
* Updated: Compatible with WP 6.6 & Woo 9.2

= 2.1.4 =
* Fixed: Price when adding order manually
* Fixed: Minor CSS/JS issues in the backend

= 2.1.3 =
* Updated: Compatible with WP 6.5 & Woo 8.9

= 2.1.2 =
* Updated: Compatible with WP 6.5 & Woo 8.8

= 2.1.1 =
* Updated: Optimized the code

= 2.1.0 =
* Updated: Compatible with WP 6.4 & Woo 8.5

= 2.0.9 =
* Updated: Optimized the code

= 2.0.8 =
* Fixed: Variation's price cache

= 2.0.7 =
* Fixed: Variation's price

= 2.0.6 =
* Added: HPOS compatibility

= 2.0.5 =
* Fixed: Missing rules when quick editing a product

= 2.0.4 =
* Fixed: Minor CSS/JS issues in the backend

= 2.0.3 =
* Updated: Optimized the code

= 2.0.2 =
* Updated: Optimized the code

= 2.0.1 =
* Fixed: Minor CSS/JS issues in the backend

= 2.0.0 =
* Added: Apply sources (category, tag, etc.)

= 1.2.0 =
* Added: Overview popup on the products list in the backend

= 1.1.0 =
* Added: Filter hook 'wpcpu_ignore'

= 1.0.2 =
* Updated: Optimized the code

= 1.0.1 =
* Added: Option to hide price and fill the custom price text

= 1.0.0 =
* Released