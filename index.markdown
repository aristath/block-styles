---
layout: home
---

Add this in `wp-content/mu-plugins/block-styles.php`:
```php
<?php

add_action( 'init', function() {
	// The URL of the remote API.
	$api_url    = 'https://aristath.github.io/block-styles/api.json';
	$cache_name = 'aristath-block-styles-api-response';
	// Get the JSON from the remote API.
	$response = get_site_transient( $cache_name );
	if ( false === $response ) {
		// TODO: Add error handling and a fallback in case the API is down.
		$response = json_decode( wp_remote_retrieve_body( wp_remote_get( $api_url ) ), true );
		// Cache the API response for a minute.
		set_site_transient( $cache_name, $response, 60 );
	}

	// Register the block styles.
	foreach ( $response as $blockName => $blockStyles ) {
		foreach ( $blockStyles as $blockStyle ) {
			register_block_style( $blockName, $blockStyle );
		}
	}
} );

```
