<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
<channel>
<title>Ugly Fish Products Feed</title>
<link>http://uglyfisheyewear.com</link>
<description>Ugly Fish Products Feed For All Available Products</description>
	<% if Products %>
	<% loop Products %>
		<item>
			<% if ClassName = SimpleProduct %>
				<g:id>$ID</g:id>
				<g:brand>Ugly Fish Eyewear</g:brand>
				<g:mpn>uf-{$ID}</g:mpn>
				<g:title>Ugly Fish $MasterProduct.Title.LimitWordCountXML(130)</g:title>
				<g:description>$MasterProduct.Content.LimitWordCountXML(4000) $MasterProduct.ProductCategoriesForGoogle.XML</g:description>
				<g:link>http://uglyfisheyewear.com{$ProductLink}</g:link>
				<g:condition>new</g:condition>
				<g:price>$PriceXML</g:price>
				<g:availability><% if $Availability == "Unavailable" %>out of stock<% else %>in stock<% end_if %></g:availability>
				<g:image_link>http://uglyfisheyewear.com{$ProductThumbnail.URL}</g:image_link>
				<g:google_product_category>Apparel &amp; Accessories &gt; Clothing Accessories &gt; Sunglasses</g:google_product_category>
			<% else %>
				<g:id>$ID</g:id>
				<g:brand>Ugly Fish Eyewear</g:brand>
				<g:mpn>uf-{$ID}</g:mpn>
				<g:title>Ugly Fish $Title.LimitWordCountXML(130)</g:title>
				<g:description>$Content.LimitWordCountXML(4000) $ProductCategoriesForGoogle.XML</g:description>
				<g:link>http://uglyfisheyewear.com{$ProductLink}</g:link>
				<g:condition>new</g:condition>
				<g:price>$PriceXML</g:price>
				<g:availability><% if $Availability == "Unavailable" %>out of stock<% else %>in stock<% end_if %></g:availability>
				<g:image_link>http://uglyfisheyewear.com{$ProductThumbnail.URL}</g:image_link>
				<g:google_product_category>Apparel &amp; Accessories &gt; Clothing Accessories &gt; Sunglasses</g:google_product_category>
			<% end_if %>
		</item>
	<% end_loop %>
	<% end_if %>
</channel>	
</rss>
