<?php
//подключаем стили родительской темы затем дочерней
add_action( 'wp_enqueue_scripts', 'my_child_theme_scripts' );
function my_child_theme_scripts() {
	wp_enqueue_style( 'twentyseventeen-style', get_template_directory_uri() . '/style.css', array( 'child-style' ) );
}

//перевод гривны в евро, курс можно задавать в админпанеле Settings->General в самом низу
function my_woocommerce_get_price($price, $_product) {

	if ( ! empty( get_option( 'currency_rate', '' ) ) && is_numeric( get_option( 'currency_rate' ) ) ){
		$new_price = $price / esc_html( get_option( 'currency_rate' ) );;
	    return $new_price;
	} else {
		return;
	}
}

add_filter('woocommerce_get_price', 'my_woocommerce_get_price',100,2);

//В админке товары в гривне на фронте в евро
if ( ! is_admin() ) {
	function add_my_currency_symbol( $currency_symbol, $currency ) { 
	    switch( $currency ) {
	        case 'UAH': $currency_symbol = '€'; break;
	    }  
	    return $currency_symbol;
	} 
	 add_filter('woocommerce_currency_symbol', 'add_my_currency_symbol', 10, 2);
}


//Добавление настройки курса валюты, Settings->General в самом низу
add_action( 'admin_init', 'phone_settings_api_init' );

function phone_settings_api_init() {
	register_setting( 'general', 'currency_rate', 'sanitize_text_field' );

	add_settings_field(
		'currency_rate',
		'<label for="currency_rate">Currency rate</label>',
		'currency_rate_field_html',
		'general'
	);
}

function currency_rate_field_html() {
	$value = get_option( 'currency_rate' );
	printf( '<input type="text" id="currency_rate" name="currency_rate" value="%s" />',
	esc_attr( $value ) );
}