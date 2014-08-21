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
				<g:title>$MasterProduct.Title.XML</g:title>
				<g:description>$MasterProduct.Content.XML</g:description>
				<g:link>http://uglyfisheyewear.com/{$ProductLink}</g:link>
				<g:condition><% if $IsNewPro %>new<% end_if %></g:condition>
				<g:price>$Price.Nice</g:price>
				<g:availability>1</g:availability>
				<g:image_link>http://uglyfisheyewear.com/{$ProductThumbnail.SetSize(224,168)}</g:image_link>
			<% else %>
				<g:id>$ID</g:id>
				<g:title>$Title.XML</g:title>
				<g:description>$Content.XML</g:description>
				<g:link>http://uglyfisheyewear.com/{$ProductLink}</g:link>
				<g:condition><% if $IsNewPro %>new<% end_if %></g:condition>
				<g:price>$Price.Nice</g:price>
				<g:availability>1</g:availability>
				<g:image_link>http://uglyfisheyewear.com/{$ProductThumbnail.SetSize(224,168)}</g:image_link>
			<% end_if %>
		</item>
	<% end_loop %>
	<% end_if %>
</channel>	
</rss>
