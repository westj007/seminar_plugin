<?php
/**
 * Plugin Name:     Peltz Seminar Product Type
 * Plugin URI:      http://finewebworking.com
 * Description:     A Woocommerce product that represents seminars.
 */


// Register the custom product type after init

function peltz_seminar_product_type(){
     // declare the product class
     class WC_Product_Peltz_Seminar extends WC_Product {

        public function __construct( $product ) {

           $this->product_type = 'peltz_seminar';
           parent::__construct( $product );

        }

    }
}
add_action( 'init', 'peltz_seminar_product_type' );


/*
    add Peltz Seminar to drop down
*/  
function add_peltz_seminar_product( $types ){
    $types[ 'peltz_seminar' ] = __( 'Peltz Seminar' );
    return $types;
}
add_filter( 'product_type_selector', 'add_peltz_seminar_product' );


/**
 * Show pricing fields for peltz_seminar product.
 */
function peltz_seminar_custom_js() {

    if ( 'product' != get_post_type() ) :
        return;
    endif;

    ?>
    <script type='text/javascript'>
        jQuery( document ).ready( function() {
            jQuery( '.options_group.pricing' ).addClass( 'show_if_peltz_seminar' ).show();
        // check which product type is selected
        var selectedProductType = jQuery('#product-type').val();
        if(selectedProductType == 'peltz_seminar') {
        // Deactivate shipping Link in left Menu
        jQuery( '.shipping_tab' ).removeClass( 'active' );
        // Hide Shipping Panel on load
        jQuery( '#shipping_product_data' ).addClass( 'hidden' ).hide();
        // Activate General Link in left Menu
        jQuery( '.general_tab' ).addClass( 'active' ).show();
        // Show General Panel on load
        jQuery( '#general_product_data').removeClass( 'hidden' ).show();
        }
        });
    </script><?php
}
add_action( 'admin_footer', 'peltz_seminar_custom_js' );



/**
 * Add a custom product tab. Hide from other products
 */
function peltz_seminar_custom_product_tabs( $tabs) {

    $tabs['seminar'] = array(
        'label'     => __( 'Seminar Dates', 'woocommerce' ),
        'target'    => 'seminar_options',
        'class'     => array( 'show_if_peltz_seminar', 'hide_if_simple', 'hide_if_variable', 'hide_if_grouped', 'hide_if_external', 'hide_if_downloadable', 'hide_if_virtual', 'hide_if_subscription', 'hide_if_peltz_adventure' ),
    );

    return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'peltz_seminar_custom_product_tabs' );


/**
 * Contents of the Peltz Seminar Seminar Dates tab.
 */
function peltz_seminar_options_product_tab_content() {

    global $post;

    ?><div id='seminar_options' class='panel woocommerce_options_panel'><?php
        ?><div class='options_group'><?php
    $thepostid = $post->ID;
    $seminar_date = ( $date = get_post_meta( $thepostid, '_seminar_date', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
    echo '<p class="form-field sale_price_dates_field">
                <label for="_seminar_date">' . __( 'Seminar Date', 'woocommerce' ) . '</label>
                <input type="text" class="short date-picker" name="_seminar_date" id="_seminar_date" value="' . esc_attr( $seminar_date ) . '" placeholder="' . 'YYYY-MM-DD" maxlength="10" pattern="\d{4}/\d{1,2}/\d{1,2})" />
            </p>';
        ?></div>
    </div><?php
}
add_action( 'woocommerce_product_data_panels', 'peltz_seminar_options_product_tab_content' );


/**
 * Save the custom fields.
 */
function peltz_seminar_save_seminar_option_field( $post_id ) {
    
    $seminar_date = ( isset( $_POST['_seminar_date'] ) ) ? strtotime( $_POST['_seminar_date'] ) : '';
    update_post_meta( $post_id, '_seminar_date', $seminar_date);
    
}

add_action( 'woocommerce_process_product_meta_peltz_seminar', 'peltz_seminar_save_seminar_option_field'  );

/**
 * Hide Attributes data panel.
 */
function peltz_seminar_hide_attributes_data_panel( $tabs) {

    $tabs['attribute']['class'][] = 'hide_if_peltz_seminar';

    return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'peltz_seminar_hide_attributes_data_panel' );