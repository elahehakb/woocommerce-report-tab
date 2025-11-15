# WooCommerce Custom “Report” Tab for Specific Product Category

This project introduces a custom **“Report” tab** to WooCommerce product pages.  
The tab appears **only when**:

1. The product belongs to a specific category (default: `workshop`)
2. The product has custom report content added from the admin meta box

This feature is ideal for displaying structured reports, summaries, workshop outcomes, course results, or any additional product-specific information.

---

## ✨ Features

- Adds a new WooCommerce product tab labeled **“Report”**
- Tab visibility depends on product category
- Includes a meta box with a full WordPress editor (`wp_editor`)
- Secure content storage using `wp_kses_post`
- Works with themes, child themes, or as part of a custom plugin
- Clean, minimal, and easy-to-maintain code

---

## 📂 Code Overview

The functionality consists of:

- Filtering WooCommerce product tabs to register a custom tab
- Rendering tab content dynamically using post meta
- Creating a custom admin meta box for report content
- Saving report data on product update

---

## 🛠 Installation

You can use the code in two ways:

### **Option 1 — Add to Theme (functions.php)**  
Paste the entire PHP code into your active theme or child theme’s `functions.php`.

### **Option 2 — Convert into a Plugin**  
Create a folder such as:

```bash
/woocommerce-report-tab/
    report-tab.php
```

---

## 🛠 Installation

### **Option 1: Add directly to your theme**
1. Copy the entire PHP code.
2. Paste it into:  
   `wp-content/themes/YOURTHEME/functions.php`

---

### **Option 2 (Recommended): Use as a mini plugin**
1. Create a folder inside:  
   `wp-content/plugins/woocommerce-report-tab/`
2. Inside it, create a file:  
   `report-tab.php`
3. Paste the entire PHP code into it.
4. Activate the plugin from **WordPress Admin → Plugins**.

---

## ⚙️ How It Works

### 1. Detects Category  
The code checks if the product belongs to a specific category:

```php
has_term( 'workshop', 'product_cat', $product->get_id() );
```
---
### 2. Adds Product Tab

If the product meets the conditions, a new tab titled **گزارش (Report)** appears on the WooCommerce product page.

---

### 3. Admin Meta Box

A meta box appears on the product edit pages in the WordPress admin.  
This meta box allows entering **report content** using WordPress’s built-in rich text editor (`wp_editor`).  
You can include text, images, or HTML content.

---

### 4. Displays the Report

Any content entered in the meta box will appear inside the **Report** tab on the frontend product page.  
The content is automatically sanitized using `wp_kses_post()` and wrapped in paragraphs with `wpautop()` for proper formatting.

---

## 📝 Example Code (Full PHP File)

Paste the following code into your plugin file or your theme’s `functions.php`:

```php
// Add "Report" tab only for a specific product category
add_filter( 'woocommerce_product_tabs', 'add_report_tab_for_specific_category' );
function add_report_tab_for_specific_category( $tabs ) {
    global $product;

    $report_content = get_post_meta( $product->get_id(), '_report_content', true );

    if ( has_term( 'workshop', 'product_cat', $product->get_id() ) && ! empty( $report_content ) ) {
        $tabs['report_tab'] = array(
            'title'    => __( 'report', 'woocommerce' ),
            'priority' => 15,
            'callback' => 'report_tab_content'
        );
    }

    return $tabs;
}

// Output the content of the "Report" tab
function report_tab_content() {
    global $post;

    $report_content = get_post_meta( $post->ID, '_report_content', true );

    if ( ! empty( $report_content ) ) {
        echo wpautop( wp_kses_post( $report_content ) );
    }
}

// Add meta box for report content in admin
add_action( 'add_meta_boxes', 'add_report_content_metabox' );
function add_report_content_metabox() {
    add_meta_box(
        'report_content_box',
        'Report Content (Product report)',
        'report_content_metabox_html',
        'product',
        'normal',
        'high'
    );
}

// Meta box editor
function report_content_metabox_html( $post ) {
    $value = get_post_meta( $post->ID, '_report_content', true );
    wp_editor( $value, 'report_content_editor', array(
        'textarea_name' => 'report_content',
        'media_buttons' => true,
        'textarea_rows' => 10,
        'teeny'         => false,
    ) );
}

// Save report content
add_action( 'save_post_product', 'save_report_content_meta' );
function save_report_content_meta( $post_id ) {
    if ( isset( $_POST['report_content'] ) ) {
        update_post_meta( $post_id, '_report_content', wp_kses_post( $_POST['report_content'] ) );
    }
}

---
### 🔧 Changing the Target Category

To display the "Report" tab for a different product category, update the following line in the PHP code:

```php
has_term( 'workshop', 'product_cat', $product->get_id() );
```
Replace `'workshop'` with the slug of your desired product category.  
For example, to show the tab for products in the `courses` category:

```php
has_term( 'courses', 'product_cat', $product->get_id() );
```
---
## 🧪 Compatibility

- WordPress 6.x+
- WooCommerce 7.x / 8.x+
- PHP 7.4+ / PHP 8+
- Works with Classic Editor and Block Editor

---

## 📜 License

MIT License — free to use, modify, and distribute.

---

## 👤 Author

Developed by **Elaheh Akbarian**  
Contributions, forks, and suggestions are welcome.

---

## 📝 Summary

This plugin/code snippet:

- Adds a conditional Report tab to WooCommerce products.
- Provides a rich-text meta box in the admin.
- Automatically displays sanitized content on the frontend.
- Fully compatible with WordPress and WooCommerce editors.

