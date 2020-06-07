# analcrickets
[![License: WTFPL](https://img.shields.io/badge/License-WTFPL-brightgreen.svg)](http://www.wtfpl.net/about/)[![PHP](https://img.shields.io/badge/Made%20with-php-9cf)](https://php.net/)

A simple website for Icecast 2. Works with any Icecast2 make your own branding.

Appropriate httpd config:

	<VirtualHost *:80>
	ServerAdmin nope@faggotry.org
	ServerName anal.cricket
	ServerAlias www.anal.cricket
	Header set Access-Control-Allow-Origin "*"
	DocumentRoot /var/www/html/radio
	ErrorLog /var/log/httpd/anal-error.log
	ProxyPass "/live" "http://localhost:8000/live"
	ProxyPassReverse "/live" "http://localhost:8000/live"
	ProxyPass "/status-json.xsl" "http://localhost:8000/status-json.xsl"
	ProxyPassReverse "/status-json.xsl" "http://localhost:8000/status-json.xsl"
	</VirtualHost>