<?php
function seo($fullpage, $lang, $uri)
{

}
function seo_product($fullpage, $lang, $uri)
{
    $snippet = '<script type="application/ld+json">
{
	"@context": "http://schema.org/",
	"@type": "Product",
	"name": "OrderLemon",
	"image": "{{featured_image}}",
	"description": "{{description}}",
	"brand": {
		"@type": "Brand",
		"name": "{{brand}}"
	},
	"offers": {
		"@type": "AggregateOffer",
		"priceCurrency": "{{currency}}",
		"lowPrice": "{{lowprice}}",
		"highPrice": "{{highprice}}",
		"url": "https://' . LIGHTWEB_PRODUCTION . '/' . $lang . '/' . $uri . '",
		"availability": "https://schema.org/InStock",
		"offerCount": "1"
	},
	"aggregateRating": {
		"@type": "AggregateRating",
		"ratingValue" : "5",
		"ratingCount" : "{{ratecount}}",
		"reviewCount" : "{{reviewcount}}",
		"worstRating" : "1",
		"bestRating" : "5"
	}
}
</script>';
}